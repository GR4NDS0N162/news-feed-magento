<?php

declare(strict_types=1);

namespace Oggetto\MultipleChoice\Model;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\Search\SearchCriteria;
use Magento\Framework\Api\Search\SearchCriteriaBuilder as CoreSearchCriteriaBuilder;

class MultipleRangesFilterPlugin
{
    /**
     * @var FilterBuilder
     */
    private FilterBuilder $filterBuilder;

    /**
     * @var FilterGroupBuilder
     */
    private FilterGroupBuilder $filterGroupBuilder;

    /**
     * @param FilterBuilder      $filterBuilder
     * @param FilterGroupBuilder $filterGroupBuilder
     */
    public function __construct(
        FilterBuilder $filterBuilder,
        FilterGroupBuilder $filterGroupBuilder,
    ) {
        $this->filterBuilder = $filterBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
    }

    public function afterCreate(CoreSearchCriteriaBuilder $subject, SearchCriteria $result): SearchCriteria
    {
        $filterGroups = $result->getFilterGroups();
        $found = false;
        foreach ($filterGroups as & $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() == 'price') {
                    $found = true;
                    break;
                }
            }
            if ($found) {
                break;
            }
        }
        if (!$found || empty($filterGroup) || empty($filter)) {
            return $result;
        }

        $priceFilters = [];
        foreach ((array) $filter->getValue() as $value) {
            $priceFilters[] = $this->filterBuilder
                ->setField($filter->getField())
                ->setValue($value)
                ->create();
        }
        $filterGroup->setFilters($priceFilters);

        $result->setFilterGroups($filterGroups);
        return $result;
    }
}
