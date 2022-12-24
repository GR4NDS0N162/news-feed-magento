<?php

declare(strict_types=1);

namespace Oggetto\News\Model\ResourceModel;

use Magento\Framework\EntityManager\EntityManager;
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
     * @var EntityManager
     */
    protected EntityManager $entityManager;

    /**
     * @param Context     $context
     * @param ProductNews $productNews
     * @param string      $connectionName
     */
    public function __construct(
        Context $context,
        ProductNews $productNews,
        EntityManager $entityManager,
        $connectionName = null,
    ) {
        $this->productNews = $productNews;
        $this->entityManager = $entityManager;
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
    public function load(AbstractModel $news, $value, $field = null): News
    {
        if ($value) {
            $this->entityManager->load($news, $value);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function save(AbstractModel $news): News
    {
        $this->entityManager->save($news);
        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function _afterSave(AbstractModel $news): News
    {
        parent::_afterSave($news);
        $this->productNews->setProductIds(
            $news->getData(Save::KEY_PRODUCTS_DATA),
            $news->getId()
        );
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function delete(AbstractModel $news): News
    {
        $this->entityManager->delete($news);
        return $this;
    }
}
