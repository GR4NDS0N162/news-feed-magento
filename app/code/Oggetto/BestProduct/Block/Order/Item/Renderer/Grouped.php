<?php

declare(strict_types=1);

namespace Oggetto\BestProduct\Block\Order\Item\Renderer;

use Magento\Catalog\Model\Product\OptionFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\View\Element\Template\Context;
use Magento\GroupedProduct\Block\Order\Item\Renderer\Grouped as GroupedRenderer;
use Oggetto\BestProduct\Api\BestOrderItemRenderer;
use Oggetto\BestProduct\Model\Config\Source\YesNoMaybe;
use Oggetto\BestProduct\Setup\Patch\Data\IsBestOrderItemPatch;

class Grouped extends GroupedRenderer implements BestOrderItemRenderer
{
    /**
     * @var YesNoMaybe
     */
    private YesNoMaybe $source;

    /**
     * @param Context       $context
     * @param StringUtils   $string
     * @param OptionFactory $productOptionFactory
     * @param YesNoMaybe    $source
     * @param array         $data
     */
    public function __construct(
        Context $context,
        StringUtils $string,
        OptionFactory $productOptionFactory,
        YesNoMaybe $source,
        array $data = []
    ) {
        $this->source = $source;
        parent::__construct($context, $string, $productOptionFactory, $data);
    }

    public function getIsBest(): string
    {
        /** @var DataObject $orderItem */
        $orderItem = $this->getOrderItem();
        return (string) $this->source->getOptionText(
            $orderItem->getData(IsBestOrderItemPatch::ATTRIBUTE_CODE)
        );
    }
}
