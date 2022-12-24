<?php

declare(strict_types=1);

namespace Oggetto\News\Model\News;

use Magento\Eav\Model\Entity\AbstractEntity;

class ProductNews extends AbstractEntity
{
    public const PRODUCT_TABLE_NAME = 'news_product';
    public const PRODUCT_ID = 'product_id';
    public const NEWS_ID = 'news_id';
    public const POSITION = 'position';

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
            $this->getConnection()->prepareSqlCondition(self::NEWS_ID, ['eq' => $newsId])
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
            $cond = $connection->prepareSqlCondition(self::NEWS_ID, ['eq' => $newsId]);
            $cond .= ' AND ' . $connection->prepareSqlCondition(self::PRODUCT_ID, ['in' => array_values($delete)]);
            $connection->delete($this->getNewsProductTable(), $cond);
        }

        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $productId) {
                $data[] = [
                    self::NEWS_ID    => (int) $newsId,
                    self::PRODUCT_ID => (int) $productId,
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
