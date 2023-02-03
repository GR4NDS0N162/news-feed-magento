<?php

declare(strict_types=1);

namespace Oggetto\MultipleChoice\Model\CatalogSearch\Model\Layer\Filter;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Filter\Item\DataBuilder;
use Magento\Catalog\Model\Layer\Filter\ItemFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\CatalogSearch\Model\Layer\Filter\Attribute as FilterAttribute;
use Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filter\StripTags;
use Magento\Store\Model\StoreManagerInterface;

class Attribute extends FilterAttribute
{
    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $collectionProvider;

    /**
     * @var StripTags
     */
    private StripTags $tagFilter;

    /**
     * @param ItemFactory           $filterItemFactory
     * @param StoreManagerInterface $storeManager
     * @param Layer                 $layer
     * @param DataBuilder           $itemDataBuilder
     * @param StripTags             $tagFilter
     * @param CollectionFactory     $collectionProvider
     * @param array                 $data
     */
    public function __construct(
        ItemFactory $filterItemFactory,
        StoreManagerInterface $storeManager,
        Layer $layer,
        DataBuilder $itemDataBuilder,
        StripTags $tagFilter,
        CollectionFactory $collectionProvider,
        array $data = [],
    ) {
        $this->tagFilter = $tagFilter;
        $this->collectionProvider = $collectionProvider;
        parent::__construct($filterItemFactory, $storeManager, $layer, $itemDataBuilder, $tagFilter, $data);
    }

    /**
     * @inheritDoc
     *
     * @throws LocalizedException
     */
    public function apply(RequestInterface $request)
    {
        $attributeValue = $request->getParam($this->_requestVar);
        if (empty($attributeValue) && !is_numeric($attributeValue)) {
            return $this;
        }

        $attribute = $this->getAttributeModel();
        /** @var Collection $productCollection */
        $productCollection = $this->getLayer()
            ->getProductCollection();
        $productCollection->addFieldToFilter(
            $attribute->getAttributeCode(),
            $this->convertAttributeValue($attribute, $attributeValue)
        );

        $labels = [];
        foreach ((array) $attributeValue as $value) {
            $label = $this->getOptionText($value);
            $labels[] = is_array($label) ? $label : [$label];
        }
        $label = implode(',', array_unique(array_merge([], ...$labels)));
        $this->getLayer()
            ->getState()
            ->addFilter($this->_createItem($label, $attributeValue));

        if (!$this->isAllowedMultipleFiltering()) {
            $this->setItems([]); // set items to disable show filtering
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function _getItemsData(): array
    {
        $attribute = $this->getAttributeModel();
        /** @var Collection $productCollection */
        $productCollection = $this->getLayer()
            ->getProductCollection();

        if ($this->isAllowedMultipleFiltering()) {
            $productCollection = $this->collectionProvider->create();
            $productCollection = $productCollection
                ->addCategoryFilter($this->getLayer()->getCurrentCategory())
                ->setStoreId($this->getStoreId());

            $optionForDelete = [];
            foreach ($this->getLayer()->getState()->getFilters() as $filterItem) {
                $filter = $filterItem->getFilter();
                $value = $filterItem->getData('value');
                if ($filter->getRequestVar() == $this->getRequestVar()) {
                    $optionForDelete = is_array($value)
                        ? array_merge($optionForDelete, array_values($value))
                        : [$value];
                    continue;
                }
                if ($filter instanceof FilterAttribute) {
                    $productCollection = $productCollection->addFieldToFilter(
                        $filter->getAttributeModel()->getAttributeCode(),
                        $this->convertAttributeValue(
                            $filter->getAttributeModel(),
                            $value
                        )
                    );
                }
            }

            $attributeValueCount = $productCollection->getAttributeValueCount($attribute->getAttributeCode());
            $optionsFacetedData = [];
            foreach ($attributeValueCount as $value => $count) {
                if (in_array($value, $optionForDelete)) {
                    continue;
                }
                $optionsFacetedData[$value] = [
                    'value' => $value,
                    'count' => (int) $count,
                ];
            }
        } else {
            $optionsFacetedData = $productCollection->getFacetedData($attribute->getAttributeCode());
        }

        $isAttributeFilterable =
            $this->getAttributeIsFilterable($attribute) === static::ATTRIBUTE_OPTIONS_ONLY_WITH_RESULTS;

        if (count($optionsFacetedData) === 0 && !$isAttributeFilterable) {
            return $this->itemDataBuilder->build();
        }

        $options = $attribute->getFrontend()->getSelectOptions();
        foreach ($options as $option) {
            $this->buildOptionData($option, $isAttributeFilterable, $optionsFacetedData);
        }

        return $this->itemDataBuilder->build();
    }

    /**
     * Build option data
     *
     * @param array   $option
     * @param boolean $isAttributeFilterable
     * @param array   $optionsFacetedData
     */
    private function buildOptionData(
        array $option,
        bool $isAttributeFilterable,
        array $optionsFacetedData,
    ) {
        $value = $this->getOptionValue($option);
        if ($value === false) {
            return;
        }
        $count = $this->getOptionCount($value, $optionsFacetedData);
        if ($isAttributeFilterable && $count === 0) {
            return;
        }

        $this->itemDataBuilder->addItemData(
            $this->tagFilter->filter($option['label']),
            $value,
            $count
        );
    }

    /**
     * Retrieve option value if it exists
     *
     * @param array $option
     * @return bool|string
     */
    private function getOptionValue(array $option)
    {
        if (empty($option['value']) && !is_numeric($option['value'])) {
            return false;
        }
        return $option['value'];
    }

    /**
     * Retrieve count of the options
     *
     * @param int|string $value
     * @param array      $optionsFacetedData
     * @return int
     */
    private function getOptionCount($value, array $optionsFacetedData): int
    {
        return isset($optionsFacetedData[$value]['count'])
            ? (int) $optionsFacetedData[$value]['count']
            : 0;
    }

    /**
     * @throws LocalizedException
     */
    private function isAllowedMultipleFiltering(): bool
    {
        return (bool) $this->getAttributeModel()->getData('allow_multiple_filtering');
    }

    /**
     * Convert attribute value according to its backend type.
     *
     * @param ProductAttributeInterface $attribute
     * @param mixed                     $value
     * @return int|string
     */
    private function convertAttributeValue(ProductAttributeInterface $attribute, mixed $value)
    {
        if ($attribute->getBackendType() === 'int') {
            return (int) $value;
        }
        return $value;
    }
}
