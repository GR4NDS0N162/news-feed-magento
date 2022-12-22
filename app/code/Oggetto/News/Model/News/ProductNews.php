<?php

declare(strict_types=1);

namespace Oggetto\News\Model\News;

use Magento\Eav\Model\Entity\AbstractEntity;

class ProductNews extends AbstractEntity
{
    public const PRODUCT_TABLE_NAME = 'news_product';
    public const PRODUCT_ID = 'product_id';

    /**
     * @var string
     */
    protected $newsProductTable;

    /**
     * Get products data associated with news
     *
     * @param string $newsId
     * @return string[]
     */
    public function getProductIdsById(string $newsId): array
    {
        $select = $this->getConnection()->select()->from(
            self::PRODUCT_TABLE_NAME,
            [self::PRODUCT_ID]
        )->where(
            'news_id = ?',
            $newsId
        );
        return $this->getConnection()->fetchCol($select);
    }

    /**
     * Save products in link table
     *
     * @param string[] $productsIds
     * @param string   $newsId
     */
    public function setProductIds(?array $productsIds, ?string $newsId)
    {
        if (is_null($productsIds) || is_null($newsId)) {
            return;
        }

        $oldProductIds = $this->getProductIdsById($newsId);
        $insert = array_diff($productsIds, $oldProductIds);
        $delete = array_diff($oldProductIds, $productsIds);

        $connection = $this->getConnection();

        if (!empty($delete)) {
            $cond = ['product_id IN (?)' => array_values($delete), 'news_id = ?' => $newsId];
            $connection->delete($this->getNewsProductTable(), $cond);
        }

        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $productId) {
                $data[] = [
                    'news_id'    => (int) $newsId,
                    'product_id' => (int) $productId,
                ];
            }
            $connection->insertMultiple($this->getNewsProductTable(), $data);
        }
    }

    public function getNewsProductTable(): string
    {
        if (!$this->newsProductTable) {
            $this->newsProductTable = $this->getTable(self::PRODUCT_TABLE_NAME);
        }
        return $this->newsProductTable;
    }
}
