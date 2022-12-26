<?php

declare(strict_types=1);

namespace Oggetto\News\Model\News;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;
use Oggetto\News\Api\Data\NewsInterface;
use Oggetto\News\Api\NewsRepositoryInterface;
use Oggetto\News\Model\NewsFactory;
use Oggetto\News\Model\ResourceModel\News\Collection as NewsCollection;
use Oggetto\News\Model\ResourceModel\News\CollectionFactory;

class DataProvider extends ModifierPoolDataProvider
{
    /**
     * @var NewsCollection
     */
    protected $collection;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var RequestInterface
     */
    protected RequestInterface $request;

    /**
     * @var NewsFactory
     */
    protected NewsFactory $newsFactory;

    /**
     * @var NewsRepositoryInterface
     */
    protected NewsRepositoryInterface $newsRepository;

    /**
     * @param string                  $name
     * @param string                  $primaryFieldName
     * @param string                  $requestFieldName
     * @param CollectionFactory       $newsCollectionFactory
     * @param RequestInterface        $request
     * @param NewsFactory             $newsFactory
     * @param NewsRepositoryInterface $newsRepository
     * @param array                   $meta
     * @param array                   $data
     * @param PoolInterface|null      $pool
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $newsCollectionFactory,
        RequestInterface $request,
        NewsFactory $newsFactory,
        NewsRepositoryInterface $newsRepository,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null,
    ) {
        $this->collection = $newsCollectionFactory->create();
        $this->request = $request;
        $this->newsFactory = $newsFactory;
        $this->newsRepository = $newsRepository;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $news = $this->getCurrentNews();
        $this->loadedData[$news->getId()] = $news->getData();

        return $this->loadedData;
    }

    private function getCurrentNews(): NewsInterface
    {
        $news = $this->newsFactory->create();

        if ($newsId = $this->request->getParam(NewsInterface::ID)) {
            try {
                $news = $this->newsRepository->getById($newsId);
            } catch (NoSuchEntityException) {
            }
        }

        return $news;
    }
}
