<?php

declare(strict_types=1);

namespace Oggetto\News\Block;

use Magento\Framework\Data\Collection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Theme\Block\Html\Pager;
use Oggetto\News\Api\Data\NewsInterface;
use Oggetto\News\Api\NewsRepositoryInterface;
use Oggetto\News\Helper\Data;
use Oggetto\News\Model\ResourceModel\News\Collection as NewsCollection;

/**
 * @method setCollection(NewsCollection $collection)
 * @method NewsCollection getCollection()
 */
class NewsList extends Template
{
    const PAGER_NAME  = 'news.news.list.pager';
    const PAGER_ALIAS = 'pager';

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
        $collection = $this->newsRepository->getList()
            ->setOrder(NewsInterface::ID, Collection::SORT_ORDER_ASC);
        $this->setCollection($collection);
    }

    /**
     * @inheritDoc
     *
     * @throws LocalizedException
     */
    protected function _prepareLayout(): NewsList
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
