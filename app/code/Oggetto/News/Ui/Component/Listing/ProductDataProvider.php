<?php

declare(strict_types=1);

namespace Oggetto\News\Ui\Component\Listing;

use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

class ProductDataProvider extends AbstractDataProvider
{
    /**
     * Product collection
     *
     * @var ProductCollection
     */
    protected $collection;

    /**
     * @param string                   $name
     * @param string                   $primaryFieldName
     * @param string                   $requestFieldName
     * @param ProductCollectionFactory $collectionFactory
     * @param array                    $meta
     * @param array                    $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ProductCollectionFactory $collectionFactory,
        array $meta = [],
        array $data = [],
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        if (!$this->collection->isLoaded()) {
            $this->collection->load();
        }
        $items = $this->collection->toArray();

        return [
            'totalRecords' => $this->collection->getSize(),
            'items'        => array_values($items),
        ];
    }
}
