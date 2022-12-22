<?php

declare(strict_types=1);

namespace Oggetto\News\Model\News;

use Magento\Eav\Model\Entity\AbstractEntity;

class ProductNews extends AbstractEntity
{
    public const PRODUCT_TABLE_NAME = 'news_product';

    /**
     * Get product ids associated with news
     *
     * @param string $newsId
     * @return string[]
     */
    public function getProductIdsById(string $newsId): array
    {
        $query = $this->getConnection()->select()->from(
            self::PRODUCT_TABLE_NAME,
            ['product_id']
        )->where(
            'news_id = ?',
            $newsId
        );
        return $this->getConnection()->fetchCol($query);
    }
}
