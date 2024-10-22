<?php

declare(strict_types=1);

namespace Oggetto\MultipleChoice\Model\CatalogSearch\Model\Layer\Filter;

use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Filter\DataProvider\Price as PriceDataProvider;
use Magento\Catalog\Model\Layer\Filter\DataProvider\PriceFactory;
use Magento\Catalog\Model\Layer\Filter\Dynamic\AlgorithmFactory;
use Magento\Catalog\Model\Layer\Filter\Item\DataBuilder;
use Magento\Catalog\Model\Layer\Filter\ItemFactory;
use Magento\CatalogSearch\Model\Layer\Filter\Price as FilterPrice;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Search\Dynamic\Algorithm;
use Magento\Store\Model\StoreManagerInterface;

class Price extends FilterPrice
{
    public const SEPARATOR = '_';

    /**
     * @var PriceDataProvider
     */
    private PriceDataProvider $dataProvider;

    /**
     * @param ItemFactory                                             $filterItemFactory
     * @param StoreManagerInterface                                   $storeManager
     * @param Layer                                                   $layer
     * @param DataBuilder                                             $itemDataBuilder
     * @param \Magento\Catalog\Model\ResourceModel\Layer\Filter\Price $resource
     * @param Session                                                 $customerSession
     * @param Algorithm                                               $priceAlgorithm
     * @param PriceCurrencyInterface                                  $priceCurrency
     * @param AlgorithmFactory                                        $algorithmFactory
     * @param PriceFactory                                            $dataProviderFactory
     * @param array                                                   $data
     */
    public function __construct(
        ItemFactory $filterItemFactory,
        StoreManagerInterface $storeManager,
        Layer $layer,
        DataBuilder $itemDataBuilder,
        \Magento\Catalog\Model\ResourceModel\Layer\Filter\Price $resource,
        Session $customerSession,
        Algorithm $priceAlgorithm,
        PriceCurrencyInterface $priceCurrency,
        AlgorithmFactory $algorithmFactory,
        PriceFactory $dataProviderFactory,
        array $data = [],
    ) {
        parent::__construct(
            $filterItemFactory,
            $storeManager,
            $layer,
            $itemDataBuilder,
            $resource,
            $customerSession,
            $priceAlgorithm,
            $priceCurrency,
            $algorithmFactory,
            $dataProviderFactory,
            $data
        );
        $this->dataProvider = $dataProviderFactory->create(['layer' => $this->getLayer()]);
    }

    /**
     * @inheritDoc
     * @throws LocalizedException
     */
    public function apply(RequestInterface $request)
    {
        $filters = $request->getParam($this->getRequestVar());
        if (empty($filters)) {
            return $this;
        }

        $validFilters = $this->getValidFilters($filters);

        $label = $this->getLabel($validFilters);
        $this->getLayer()->getState()->addFilter(
            $this->_createItem($label, $filters)
        );

        $condition = $this->prepareCondition($validFilters);

        $this->getLayer()->getProductCollection()->addFieldToFilter(
            $this->getAttributeModel()->getAttributeCode(),
            $condition
        );

        return $this;
    }

    /**
     * @param string[] $filters
     * @return array
     */
    private function getValidFilters(array $filters): array
    {
        $validFilters = [];
        foreach ($filters as $filter) {
            $filter = $this->dataProvider->validateFilter($filter);
            if (!is_bool($filter) && $filter) {
                $validFilters[] = $filter;
            }
        }
        return $validFilters;
    }

    /**
     * @param array $filterPairs
     * @return string
     */
    private function getLabel(array $filterPairs): string
    {
        $labels = [];
        foreach ($filterPairs as $filter) {
            $labels[] = $this->_renderRangeLabel(
                empty($filter[0]) ? 0 : $filter[0],
                $filter[1]
            );
        }
        return implode(', ', $labels);
    }

    /**
     * @param array $filterPairs
     * @return string[]
     */
    private function prepareCondition(array $filterPairs): array
    {
        $condition = [
            'from' => [],
            'to'   => [],
        ];
        foreach ($filterPairs as $pair) {
            [$from, $to] = $pair;

            $condition['from'][] = $from;
            $condition['to'][] = empty($to) || $from == $to ? $to : $to - self::PRICE_DELTA;
        }
        $condition['from'] = implode(self::SEPARATOR, $condition['from']);
        $condition['to'] = implode(self::SEPARATOR, $condition['to']);
        return $condition;
    }
}
