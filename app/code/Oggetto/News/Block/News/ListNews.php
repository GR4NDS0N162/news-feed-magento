<?php

declare(strict_types=1);

namespace Oggetto\News\Block\News;

use Magento\Framework\Data\Collection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Theme\Block\Html\Pager;
use Oggetto\News\Api\Data\NewsInterface;
use Oggetto\News\Api\NewsRepositoryInterface;
use Oggetto\News\Helper\Data;
use Oggetto\News\Model\News;
use Oggetto\News\Model\ResourceModel\News\Collection as NewsCollection;

/**
 * @method setCollection(NewsCollection $collection)
 * @method NewsCollection getCollection()
 */
class ListNews extends Template
{
    public const PAGER_NAME          = 'news_list_pager';
    public const PAGER_ALIAS         = 'pager';
    public const KEY_ORDER_DIRECTION = 'order_direction';

    /**
     * @var NewsRepositoryInterface
     */
    protected NewsRepositoryInterface $newsRepository;

    /**
     * @var Data
     */
    protected Data $dataHelper;

    /**
     * @param Template\Context $context
     * @param NewsRepositoryInterface $newsRepository
     * @param Data $dataHelper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        NewsRepositoryInterface $newsRepository,
        Data $dataHelper,
        array $data = [],
    ) {
        $this->newsRepository = $newsRepository;
        $this->dataHelper = $dataHelper;
        parent::__construct($context, $data);
    }

    /**
     * @inheritDoc
     *
     * @throws LocalizedException
     */
    protected function _construct()
    {
        parent::_construct();
        $orderDirection = $this->getOrderDirection();
        $collection = $this->newsRepository->getList()
            ->addFilter(NewsInterface::STATUS, News::STATUS_ENABLED)
            ->setOrder(NewsInterface::CREATION_TIME, $orderDirection);
        $this->setCollection($collection);
    }

    /**
     * Get order by
     *
     * @return string
     */
    public function getOrderDirection(): string
    {
        $orderBy = $this->getRequest()->getParam(self::KEY_ORDER_DIRECTION);
        if (isset($orderBy) && $orderBy == Collection::SORT_ORDER_ASC) {
            return Collection::SORT_ORDER_ASC;
        } else {
            return Collection::SORT_ORDER_DESC;
        }
    }

    /**
     * @inheritDoc
     *
     * @throws LocalizedException
     */
    protected function _prepareLayout(): ListNews
    {
        parent::_prepareLayout();

        /** @var Pager $pager */
        $pager = $this->getLayout()->createBlock(Pager::class, self::PAGER_NAME);
        $pager->setLimit($this->dataHelper->getNewsPerPage())
            ->setShowPerPage(false)
            ->setCollection($this->getCollection());
        $this->setChild(self::PAGER_ALIAS, $pager);
        $this->getCollection()->load();

        return $this;
    }

    /**
     * Get pager html
     *
     * @return string
     */
    public function getPagerHtml(): string
    {
        return $this->getChildHtml(self::PAGER_ALIAS);
    }
}
