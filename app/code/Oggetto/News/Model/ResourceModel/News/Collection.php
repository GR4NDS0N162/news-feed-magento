<?php

declare(strict_types=1);

namespace Oggetto\News\Model\ResourceModel\News;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Oggetto\News\Api\Data\NewsInterface;
use Oggetto\News\Model\News;
use Oggetto\News\Model\ResourceModel\News as NewsResource;
use Psr\Log\LoggerInterface;

class Collection extends AbstractCollection
{
    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @param EntityFactoryInterface $entityFactory
     * @param LoggerInterface        $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ManagerInterface       $eventManager
     * @param StoreManagerInterface  $storeManager
     * @param AdapterInterface|null  $connection
     * @param AbstractDb|null        $resource
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        StoreManagerInterface $storeManager,
        AdapterInterface $connection = null,
        AbstractDb $resource = null,
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    /**
     * Filter out news that does not match the store
     *
     * @param string $storeId
     */
    public function filterByStore($storeId)
    {
        $where = implode(' AND ', [
            'id=news_id',
            $this->getConnection()->prepareSqlCondition(
                NewsResource::STORE_ID,
                ['eq' => $storeId]
            ),
        ]);
        $this->join(
            NewsResource::NEWS_STORE_TABLE_NAME,
            $where,
            []
        );
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(News::class, NewsResource::class);
    }

    /**
     * @inheritDoc
     *
     * @throws NoSuchEntityException
     */
    protected function _afterLoad()
    {
        $this->performAfterLoad();
        return parent::_afterLoad();
    }

    /**
     * Perform operations after collection load
     *
     * @throws NoSuchEntityException
     */
    private function performAfterLoad()
    {
        $linkedIds = $this->getColumnValues(NewsInterface::ID);
        if (count($linkedIds)) {
            $connection = $this->getConnection();
            $select = $connection->select()->from(
                $this->getTable(NewsResource::NEWS_STORE_TABLE_NAME)
            )->where(
                $connection->prepareSqlCondition(NewsResource::NEWS_ID, ['in' => $linkedIds])
            );
            $result = $connection->fetchAll($select);
            if ($result) {
                $storesData = [];
                foreach ($result as $storeData) {
                    $storesData[$storeData[NewsResource::NEWS_ID]][] = $storeData[NewsResource::STORE_ID];
                }

                foreach ($this as $item) {
                    $linkedId = $item->getData(NewsInterface::ID);
                    if (!isset($storesData[$linkedId])) {
                        continue;
                    }
                    $storeIdKey = in_array(Store::DEFAULT_STORE_ID, $storesData[$linkedId], true);
                    if ($storeIdKey !== false) {
                        $stores = $this->storeManager->getStores(false, true);
                        $storeId = current($stores)->getId();
                        $storeCode = key($stores);
                    } else {
                        $storeId = current($storesData[$linkedId]);
                        $storeCode = $this->storeManager->getStore($storeId)->getCode();
                    }
                    $item->setData('_first_store_id', $storeId);
                    $item->setData('store_code', $storeCode);
                    $item->setData('store_id', $storesData[$linkedId]);
                }
            }
        }
    }
}
