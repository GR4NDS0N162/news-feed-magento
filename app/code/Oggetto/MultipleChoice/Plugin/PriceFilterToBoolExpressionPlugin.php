<?php

declare(strict_types=1);

namespace Oggetto\MultipleChoice\Plugin;

use Magento\Framework\Search\Request\Filter\Range;
use Magento\Framework\Search\Request\Filter\RangeFactory;
use Magento\Framework\Search\Request\Mapper;
use Magento\Framework\Search\Request\Query\BoolExpression;
use Magento\Framework\Search\Request\Query\BoolExpressionFactory;
use Magento\Framework\Search\Request\Query\Filter;
use Magento\Framework\Search\Request\Query\FilterFactory;
use Magento\Framework\Search\Request\QueryInterface;
use Oggetto\MultipleChoice\Model\CatalogSearch\Model\Layer\Filter\Price;

class PriceFilterToBoolExpressionPlugin
{
    /**
     * @var FilterFactory
     */
    private FilterFactory $filterFactory;

    /**
     * @var RangeFactory
     */
    private RangeFactory $rangeFactory;

    /**
     * @var BoolExpressionFactory
     */
    private BoolExpressionFactory $boolExpressionFactory;

    /**
     * @param FilterFactory         $filterFactory
     * @param RangeFactory          $rangeFactory
     * @param BoolExpressionFactory $boolExpressionFactory
     */
    public function __construct(
        FilterFactory $filterFactory,
        RangeFactory $rangeFactory,
        BoolExpressionFactory $boolExpressionFactory,
    ) {
        $this->filterFactory = $filterFactory;
        $this->rangeFactory = $rangeFactory;
        $this->boolExpressionFactory = $boolExpressionFactory;
    }

    public function afterGetRootQuery(Mapper $subject, QueryInterface $result): QueryInterface
    {
        if (!$result instanceof BoolExpression) {
            return $result;
        }
        $foundItem = false;
        foreach ($result->getMust() as $item) {
            if ($item->getName() == 'price') {
                $foundItem = $item;
                break;
            }
        }
        if (!$foundItem && !($foundItem instanceof Filter)) {
            return $result;
        }

        /** @var Range $reference */
        $reference = $foundItem->getReference();

        $pairs = $this->getPairs($reference);
        $filters = $this->getFilters($pairs, $foundItem, $reference);

        return $this->getBoolExpression($result, $foundItem, $filters);
    }

    /**
     * @param array  $pairs
     * @param Filter $foundItem
     * @param Range  $reference
     * @return Filter[]
     */
    private function getFilters(array $pairs, Filter $foundItem, Range $reference): array
    {
        $filters = [];
        foreach ($pairs as $pair) {
            $filters[] = $this->filterFactory->create([
                'name'          => $foundItem->getName(),
                'boost'         => $foundItem->getBoost() ?? 1,
                'referenceType' => $foundItem->getReferenceType(),
                'reference'     => $this->rangeFactory->create([
                    'name'  => $reference->getName(),
                    'field' => $reference->getField(),
                    'from'  => $pair['from'],
                    'to'    => $pair['to'],
                ]),
            ]);
        }
        return $filters;
    }

    /**
     * @param BoolExpression $result
     * @param Filter         $foundItem
     * @param Filter[]       $filters
     * @return BoolExpression
     */
    private function getBoolExpression(BoolExpression $result, Filter $foundItem, array $filters): BoolExpression
    {
        $must = $result->getMust();
        $must[$foundItem->getName()] = $this->boolExpressionFactory->create([
            'name'   => $foundItem->getName(),
            'boost'  => $foundItem->getBoost() ?? 1,
            'should' => $filters,
        ]);

        return $this->boolExpressionFactory->create([
            'name'    => $result->getName(),
            'boost'   => $result->getBoost() ?? 1,
            'should'  => $result->getShould(),
            'must'    => $must,
            'mustNot' => $result->getMustNot(),
        ]);
    }

    private function getPairs(Range $reference): array
    {
        $from = explode(Price::SEPARATOR, (string) $reference->getFrom());
        $to = explode(Price::SEPARATOR, (string) $reference->getTo());
        $from = array_map(fn($value) => floatval($value), $from);
        $to = array_map(fn($value) => floatval($value), $to);
        sort($from);
        sort($to);

        $pairs = [];
        $i = 0;
        while ($i < count($from)) {
            $pairs[] = [
                'from' => $from[$i],
                'to'   => $to[$i],
            ];
            $i++;
        }
        return $pairs;
    }
}
