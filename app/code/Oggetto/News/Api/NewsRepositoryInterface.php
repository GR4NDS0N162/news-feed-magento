<?php

namespace Oggetto\News\Api;

use Magento\Framework\Exception\LocalizedException;
use Oggetto\News\Api\Data\NewsInterface;
use Oggetto\News\Model\ResourceModel\News\Collection;

interface NewsRepositoryInterface
{
    /**
     * Receive news by id
     *
     * @param int $newsId
     * @return NewsInterface
     * @throws LocalizedException
     */
    public function getById($newsId);

    /**
     * Save news
     *
     * @param NewsInterface $news
     * @return NewsInterface
     * @throws LocalizedException
     */
    public function save($news);

    /**
     * Retrieve news collection
     *
     * @return Collection
     */
    public function getList();
}
