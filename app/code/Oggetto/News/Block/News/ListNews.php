<?php

declare(strict_types=1);

namespace Oggetto\News\Block\News;

use Magento\Framework\Data\Collection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Theme\Block\Html\Pager;
use Oggetto\News\Api\Data\NewsInterface;
use Oggetto\News\Api\NewsRepositoryInterface;
use Oggetto\News\Model\Config\Data;
use Oggetto\News\Model\News;
use Oggetto\News\Model\ResourceModel\News\Collection as NewsCollection;

/**
 * @method setCollection(NewsCollection $collection)
 * @method NewsCollection getCollection()
 */
class ListNews extends Template
{
    public const PAGER_ALIAS = 'pager';
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
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @param Context                 $context
     * @param NewsRepositoryInterface $newsRepository
     * @param Data                    $dataHelper
     * @param StoreManagerInterface   $storeManager
     * @param array                   $data
     */
    public function __construct(
        Template\Context $context,
        NewsRepositoryInterface $newsRepository,
        Data $dataHelper,
        StoreManagerInterface $storeManager,
        array $data = [],
    ) {
        $this->newsRepository = $newsRepository;
        $this->dataHelper = $dataHelper;
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    public function getOrderDirection(): string
    {
        $orderBy = $this->getRequest()->getParam(self::KEY_ORDER_DIRECTION);
        return ($orderBy === Collection::SORT_ORDER_ASC)
            ? Collection::SORT_ORDER_ASC
            : Collection::SORT_ORDER_DESC;
    }

    public function getSorterUrl(): string
    {
        return $this->getUrl(
            'news/news/index',
            ($this->getOrderDirection() === Collection::SORT_ORDER_DESC)
                ? [self::KEY_ORDER_DIRECTION => Collection::SORT_ORDER_ASC]
                : [],
        );
    }

    public function getImageUrl(string $imagePath): string
    {
        return $this->_urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]) . $imagePath;
    }

    public function getViewNewsUrl(string $newsId): string
    {
        return $this->getUrl(
            'news/news/view',
            [NewsInterface::ID => $newsId],
        );
    }

    /**
     * Convert date to 'l, F d, Y' format.
     *
     * Example: Tuesday, December 06, 2022
     *
     * @param string $date
     * @return string
     */
    public function convertDateFormat(string $date): string
    {
        return date("l, F d, Y", strtotime($date));
    }

    public function getPagerHtml(): string
    {
        return $this->getChildHtml(self::PAGER_ALIAS);
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
        $collection = $this->newsRepository->getList($this->storeManager->getStore()->getId())
            ->addFilter(NewsInterface::STATUS, News::STATUS_ENABLED)
            ->setOrder(NewsInterface::CREATION_TIME, $orderDirection);
        $this->setCollection($collection);
    }

    /**
     * @inheritDoc
     */
    protected function _prepareLayout(): ListNews
    {
        parent::_prepareLayout();

        /** @var Pager $pager */
        $pager = $this->getChildBlock(self::PAGER_ALIAS);
        $pager->setLimit($this->dataHelper->getNewsPerPage())
            ->setShowPerPage(false)
            ->setCollection($this->getCollection());
        $this->setChild(self::PAGER_ALIAS, $pager);
        $this->getCollection()->load();

        return $this;
    }
}
