<?php

declare(strict_types=1);

namespace Oggetto\News\Model\News;

use Magento\Eav\Model\Entity\AbstractEntity;

class ProductNews extends AbstractEntity
{
    /**
     * Get product ids associated with news
     *
     * @param string $newsId
     * @return string[]
     */
    public function getProductIdsById(string $newsId): array
    {
        return ['1', '5', '3', '7']; // TODO: Implement getProductIdsById() method.
    }
}
