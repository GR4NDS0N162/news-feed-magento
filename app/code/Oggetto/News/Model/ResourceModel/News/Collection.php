<?php

declare(strict_types=1);

namespace Oggetto\News\Model\ResourceModel\News;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Oggetto\News\Model\News;
use Oggetto\News\Model\ResourceModel\News as NewsResource;

class Collection extends AbstractCollection
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(News::class, NewsResource::class);
    }
}
