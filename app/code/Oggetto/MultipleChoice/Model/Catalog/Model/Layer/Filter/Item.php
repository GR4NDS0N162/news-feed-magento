<?php

declare(strict_types=1);

namespace Oggetto\MultipleChoice\Model\Catalog\Model\Layer\Filter;

use Magento\Catalog\Model\Layer\Filter\Item as FilterItem;
use Magento\Framework\Exception\LocalizedException;

class Item extends FilterItem
{
    /**
     * @inheritDoc
     *
     * @throws LocalizedException
     */
    public function getUrl(): string
    {
        $values = [$this->getData('value')];
        foreach ($this->getFilter()->getLayer()->getState()->getFilters() as $filterItem) {
            $value = $filterItem->getData('value');
            if ($this->getFilter()->getRequestVar() == $filterItem->getFilter()->getRequestVar()) {
                $values = array_merge($values, array_values($value));
            }
        }
        $query = [
            $this->getFilter()->getRequestVar()      => $values,
            $this->_htmlPagerBlock->getPageVarName() => null,
        ];
        return $this->_url->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $query]);
    }
}
