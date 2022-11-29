<?php

declare(strict_types=1);

namespace Oggetto\News\Block\News;

use Magento\Framework\Data\Collection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
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
        return ($orderBy === Collection::SORT_ORDER_ASC)
            ? Collection::SORT_ORDER_ASC
            : Collection::SORT_ORDER_DESC;
    }

    /**
     * Get sorter url
     *
     * @return string
     */
    public function getSorterUrl(): string
    {
        return $this->getUrl(
            'news/news/index',
            ($this->getOrderDirection() === Collection::SORT_ORDER_DESC)
                ? [self::KEY_ORDER_DIRECTION => Collection::SORT_ORDER_ASC]
                : [],
        );
    }

    /**
     * Get image url
     *
     * @param string $imagePath
     * @return string
     */
    public function getImageUrl(string $imagePath): string
    {
        return $this->_urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]) . $imagePath;
    }

    /**
     * Convert date format
     *
     * @param string $date
     * @return string
     */
    public function convertDateFormat(string $date): string
    {
        return date("l, F d, Y", strtotime($date));
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
