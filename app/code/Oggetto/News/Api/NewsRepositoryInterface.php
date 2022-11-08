<?php

namespace Oggetto\News\Api;

use Magento\Framework\Exception\LocalizedException;
use Oggetto\News\Api\Data\NewsInterface;

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
}
