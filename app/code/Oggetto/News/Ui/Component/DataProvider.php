<?php

declare(strict_types=1);

namespace Oggetto\News\Ui\Component;

use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Model\AbstractModel;

class DataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    /**
     * @inheritDoc
     */
    protected function searchResultToOutput(SearchResultInterface $searchResult): array
    {
        $resultArray = [];

        $resultArray['items'] = [];
        foreach ($searchResult->getItems() as $item) {
            if ($item instanceof AbstractModel) {
                $resultArray['items'][] = $item->getData();
            }
        }

        $resultArray['totalRecords'] = $searchResult->getTotalCount();

        return $resultArray;
    }
}
