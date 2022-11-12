<?php

namespace Oggetto\News\Api;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Oggetto\News\Api\Data\NewsInterface;
use Oggetto\News\Model\ResourceModel\News\Collection;

interface NewsRepositoryInterface
{
    /**
     * Retrieve news by ID
     *
     * @param string $newsId
     * @return NewsInterface
     * @throws NoSuchEntityException
     */
    public function getById($newsId);

    /**
     * Save news
     *
     * @param NewsInterface $news
     * @return NewsInterface
     * @throws CouldNotSaveException
     */
    public function save(NewsInterface $news);

    /**
     * Retrieve news collection
     *
     * @return Collection
     * @throws LocalizedException
     */
    public function getList();

    /**
     * Delete news
     *
     * @param NewsInterface $news
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function delete(NewsInterface $news);

    /**
     * Delete news by ID
     *
     * @param string $newsId
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function deleteById($newsId);
}
