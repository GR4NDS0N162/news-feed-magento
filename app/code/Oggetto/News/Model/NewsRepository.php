<?php

namespace Oggetto\News\Model;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Oggetto\News\Api\Data\NewsInterfaceFactory;
use Oggetto\News\Api\NewsRepositoryInterface;
use Oggetto\News\Model\ResourceModel\News as ResourceNews;

class NewsRepository implements NewsRepositoryInterface
{
    /**
     * @var ResourceNews
     */
    protected $resource;
    /**
     * @var NewsFactory
     */
    protected $newsFactory;
    /**
     * @var NewsInterfaceFactory
     */
    protected $dataNewsFactory;

    /**
     * @param ResourceNews $resource
     * @param NewsFactory $newsFactory
     * @param NewsInterfaceFactory $dataNewsFactory
     */
    public function __construct(
        ResourceNews $resource,
        NewsFactory $newsFactory,
        NewsInterfaceFactory $dataNewsFactory,
    ) {
        $this->resource = $resource;
        $this->newsFactory = $newsFactory;
        $this->dataNewsFactory = $dataNewsFactory;
    }

    /**
     * @inheritDoc
     */
    public function getById($newsId)
    {
        $news = $this->newsFactory->create();
        $this->resource->load($news, $newsId);
        if (!$news->getId()) {
            throw new NoSuchEntityException(__('The news with the "%1" ID doesn\'t exist.', $newsId));
        }
        return $news;
    }

    /**
     * @inheritDoc
     */
    public function save($news)
    {
        try {
            $this->resource->save($news);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $news;
    }
}
