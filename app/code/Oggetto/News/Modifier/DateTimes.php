<?php

namespace Oggetto\News\Modifier;

use Magento\Ui\DataProvider\Modifier\ModifierInterface;

class DateTimes implements ModifierInterface
{
    /**
     * @inheritDoc
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * @inheritDoc
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }
}
