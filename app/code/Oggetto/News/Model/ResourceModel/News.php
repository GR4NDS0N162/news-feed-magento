<?php

declare(strict_types=1);

namespace Oggetto\News\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Oggetto\News\Api\Data\NewsInterface;
use Oggetto\News\Controller\Adminhtml\News\Save;
use Oggetto\News\Model\News\ProductNews;

class News extends AbstractDb
{
    /**
     * @var ProductNews
     */
    protected ProductNews $productNews;

    /**
     * @param Context     $context
     * @param ProductNews $productNews
     * @param string      $connectionName
     */
    public function __construct(
        Context $context,
        ProductNews $productNews,
        $connectionName = null,
    ) {
        $this->productNews = $productNews;
        parent::__construct($context, $connectionName);
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('news', NewsInterface::ID);
    }

    /**
     * @inheritDoc
     */
    protected function _afterSave(AbstractModel $object): News
    {
        parent::_afterSave($object);
        $this->productNews->setProductIds(
            $object->getData(Save::KEY_PRODUCTS_DATA),
            $object->getId()
        );
        return $this;
    }
}
