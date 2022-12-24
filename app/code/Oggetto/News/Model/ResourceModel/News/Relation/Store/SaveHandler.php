<?php

declare(strict_types=1);

namespace Oggetto\News\Model\ResourceModel\News\Relation\Store;

use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Oggetto\News\Model\ResourceModel\News;

class SaveHandler implements ExtensionInterface
{
    /**
     * @var MetadataPool
     */
    protected MetadataPool $metadataPool;

    /**
     * @var News
     */
    protected News $resourceNews;

    /**
     * @param MetadataPool $metadataPool
     * @param News         $resourceNews
     */
    public function __construct(
        MetadataPool $metadataPool,
        News $resourceNews,
    ) {
        $this->metadataPool = $metadataPool;
        $this->resourceNews = $resourceNews;
    }

    /**
     * @inheritDoc
     */
    public function execute($entity, $arguments = [])
    {
        // TODO: Implement execute() method.
        return $entity;
    }
}
