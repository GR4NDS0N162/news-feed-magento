<?php

declare(strict_types=1);

namespace Oggetto\News\Block\News\Product;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Helper\Output as OutputHelper;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Url\Helper\Data;
use Oggetto\News\Api\Data\NewsInterface;
use Oggetto\News\Api\NewsRepositoryInterface;
use Oggetto\News\Model\NewsFactory;

class Related extends ListProduct
{
    public const TYPE_PRODUCT_BASE_IMAGE = 'product_base_image';

    /**
     * @var NewsRepositoryInterface
     */
    protected NewsRepositoryInterface $newsRepository;

    /**
     * @var NewsFactory
     */
    protected NewsFactory $newsFactory;

    /**
     * @var ProductCollectionFactory
     */
    protected ProductCollectionFactory $productCollectionFactory;

    /**
     * @var NewsInterface
     */
    protected NewsInterface $news;

    /**
     * @var ImageHelper
     */
    protected ImageHelper $imageHelper;

    /**
     * @param Context                     $context
     * @param PostHelper                  $postDataHelper
     * @param Resolver                    $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param Data                        $urlHelper
     * @param NewsRepositoryInterface     $newsRepository
     * @param NewsFactory                 $newsFactory
     * @param ProductCollectionFactory    $productCollectionFactory
     * @param ImageHelper                 $imageHelper
     * @param array                       $data
     * @param OutputHelper|null           $outputHelper
     */
    public function __construct(
        Context $context,
        PostHelper $postDataHelper,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        Data $urlHelper,
        NewsRepositoryInterface $newsRepository,
        NewsFactory $newsFactory,
        ProductCollectionFactory $productCollectionFactory,
        ImageHelper $imageHelper,
        array $data = [],
        ?OutputHelper $outputHelper = null,
    ) {
        $this->newsRepository = $newsRepository;
        $this->newsFactory = $newsFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->imageHelper = $imageHelper;
        parent::__construct(
            $context,
            $postDataHelper,
            $layerResolver,
            $categoryRepository,
            $urlHelper,
            $data,
            $outputHelper
        );
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        parent::_construct();

        $this->news = $this->newsFactory->create();
        if ($newsId = $this->getRequest()->getParam(NewsInterface::ID)) {
            try {
                $this->news = $this->newsRepository->getById($newsId);
            } catch (NoSuchEntityException) {
                $this->news = $this->newsFactory->create();
            }
        }
    }

    /**
     * @inheritDoc
     *
     * @throws LocalizedException
     */
    protected function _getProductCollection(): ProductCollection
    {
        if ($this->_productCollection === null) {
            $collection = $this->productCollectionFactory->create();

            $collection->addAttributeToSelect(
                'small_image'
            )->addAttributeToSelect(
                'name'
            )->addAttributeToSelect(
                'sku'
            )->addAttributeToSelect(
                'visibility'
            )->addAttributeToSelect(
                'status'
            )->addAttributeToSelect(
                'price'
            )->joinField(
                'position',
                'news_product',
                'position',
                'product_id=entity_id',
                'news_id=' . $this->news->getId(),
                'inner'
            );

            $this->_productCollection = $collection;
        }
        return $this->_productCollection;
    }

    /**
     * @inheritDoc
     */
    public function getReviewsSummaryHtml(
        Product $product,
        $templateType = false,
        $displayIfNoReviews = false,
    ): string {
        return '';
    }
}
