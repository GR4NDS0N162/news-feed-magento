<?php

declare(strict_types=1);

namespace Oggetto\News\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Oggetto\News\Api\Data\NewsInterface;
use Oggetto\News\Api\NewsRepositoryInterface;
use Oggetto\News\Model\ResourceModel\News as ResourceNews;
use Oggetto\News\Model\ResourceModel\News\Collection;
use Oggetto\News\Model\ResourceModel\News\CollectionFactory;

class NewsRepository implements NewsRepositoryInterface
{
    /**
     * @var ResourceNews
     */
    protected ResourceNews $resource;
    /**
     * @var NewsFactory
     */
    protected NewsFactory $newsFactory;
    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $newsCollectionFactory;

    /**
     * @param ResourceNews $resource
     * @param NewsFactory $newsFactory
     * @param CollectionFactory $newsCollectionFactory
     */
    public function __construct(
        ResourceNews $resource,
        NewsFactory $newsFactory,
        CollectionFactory $newsCollectionFactory,
    ) {
        $this->resource = $resource;
        $this->newsFactory = $newsFactory;
        $this->newsCollectionFactory = $newsCollectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function getById(string $newsId): News
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
    public function save(NewsInterface $news): NewsInterface
    {
        try {
            $this->resource->save($news);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $news;
    }

    /**
     * @inheritDoc
     */
    public function getList(): Collection
    {
        return $this->newsCollectionFactory->create();
    }

    /**
     * @inheritDoc
     */
    public function delete(NewsInterface $news): bool
    {
        try {
            $this->resource->delete($news);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * @inheritDoc
     *
     * @throws NoSuchEntityException
     */
    public function deleteById(string $newsId): bool
    {
        return $this->delete($this->getById($newsId));
    }
}
