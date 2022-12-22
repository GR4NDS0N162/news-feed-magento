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

    /**
     * Save products in link table
     *
     * @param string[] $productsIds
     * @param string   $newsId
     */
    public function setProductIds(array $productsIds, string $newsId)
    {
        // TODO: Implement setProductIds() method.
    }

    /**
     * Calculate arrays to save changes in the table.
     *
     * This intersects arrays, and returns array in the format:
     * [$insert, $delete, $update].
     *
     * @param array $newArray
     * @param array $oldArray
     * @return array
     */
    private function calculateDiffs(array $newArray, array $oldArray): array
    {
        $insert = array_diff_key($newArray, $oldArray);
        $delete = array_diff_key($oldArray, $newArray);

        $update = array_intersect_key($newArray, $oldArray);
        $update = array_diff_assoc($update, $oldArray);

        return [$insert, $delete, $update];
    }
}
