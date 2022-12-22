<?php

declare(strict_types=1);

namespace Oggetto\News\Model\News;

use Magento\Eav\Model\Entity\AbstractEntity;

class ProductNews extends AbstractEntity
{
    public const PRODUCT_TABLE_NAME = 'news_product';
    public const PRODUCT_ID = 'product_id';
    public const POSITION = 'position';

    /**
     * Get products data associated with news
     *
     * @param string $newsId
     * @return string[]
     */
    public function getProductsDataById(string $newsId): array
    {
        $select = $this->getConnection()->select()->from(
            self::PRODUCT_TABLE_NAME,
            [self::PRODUCT_ID, self::POSITION]
        )->where(
            'news_id = ?',
            $newsId
        );
        return $this->getConnection()->fetchAll($select);
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

    /**
     * Get product ids associated with news
     *
     * @param string $newsId
     * @return string[]
     */
    public function getProductIdsById(string $newsId): array
    {
        $productsData = $this->getProductsDataById($newsId);
        return array_column($productsData, self::PRODUCT_ID);
    }
}
