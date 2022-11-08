<?php

namespace Oggetto\News\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Oggetto\News\Api\Data\NewsInterface;

class News extends AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('news', NewsInterface::ID);
    }
}
