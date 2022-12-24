<?php

declare(strict_types=1);

namespace Oggetto\News\Model\ResourceModel\News\Relation\Store;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Oggetto\News\Api\Data\NewsInterface;
use Oggetto\News\Model\ResourceModel\News;

class ReadHandler implements ExtensionInterface
{
    /**
     * @var News
     */
    protected News $resourceNews;

    /**
     * @param News $resourceNews
     */
    public function __construct(
        News $resourceNews,
    ) {
        $this->resourceNews = $resourceNews;
    }

    /**
     * @inheritDoc
     */
    public function execute($news, $arguments = [])
    {
        if ($news->getId()) {
            $stores = $this->resourceNews->lookupStoreIds((int) $news->getId());
            $news->setData(NewsInterface::STORES, $stores);
        }
        return $news;
    }
}
