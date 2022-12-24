<?php

declare(strict_types=1);

namespace Oggetto\News\Model\News;

use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;
use Oggetto\News\Model\News;
use Oggetto\News\Model\ResourceModel\News\Collection as NewsCollection;
use Oggetto\News\Model\ResourceModel\News\CollectionFactory;

class DataProvider extends ModifierPoolDataProvider
{
    /**
     * @var NewsCollection
     */
    protected $collection;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @param string                 $name
     * @param string                 $primaryFieldName
     * @param string                 $requestFieldName
     * @param CollectionFactory      $newsCollectionFactory
     * @param array                  $meta
     * @param array                  $data
     * @param PoolInterface|null     $pool
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $newsCollectionFactory,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null,
    ) {
        $this->collection = $newsCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var News $news */
        foreach ($items as $news) {
            $this->loadedData[$news->getId()] = $news->getData();
        }

        if (!empty($data)) {
            $news = $this->collection->getNewEmptyItem();
            $news->setData($data);
            $this->loadedData[$news->getId()] = $news->getData();
        }

        return $this->loadedData;
    }
}
