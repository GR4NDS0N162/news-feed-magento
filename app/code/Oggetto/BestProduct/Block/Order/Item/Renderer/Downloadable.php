<?php

declare(strict_types=1);

namespace Oggetto\BestProduct\Block\Order\Item\Renderer;

use Magento\Catalog\Model\Product\OptionFactory;
use Magento\Downloadable\Block\Sales\Order\Item\Renderer\Downloadable as DownloadableRenderer;
use Magento\Downloadable\Model\Link\PurchasedFactory;
use Magento\Downloadable\Model\ResourceModel\Link\Purchased\Item\CollectionFactory;
use Magento\Downloadable\Model\Sales\Order\Link\Purchased;
use Magento\Framework\DataObject;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\View\Element\Template\Context;
use Oggetto\BestProduct\Api\BestOrderItemRenderer;
use Oggetto\BestProduct\Model\Config\Source\YesNoMaybe;
use Oggetto\BestProduct\Setup\Patch\Data\IsBestOrderItemPatch;

class Downloadable extends DownloadableRenderer implements BestOrderItemRenderer
{
    /**
     * @var YesNoMaybe
     */
    private YesNoMaybe $source;

    /**
     * @param Context           $context
     * @param StringUtils       $string
     * @param OptionFactory     $productOptionFactory
     * @param PurchasedFactory  $purchasedFactory
     * @param CollectionFactory $itemsFactory
     * @param YesNoMaybe        $source
     * @param array             $data
     * @param Purchased|null    $purchasedLink
     */
    public function __construct(
        Context $context,
        StringUtils $string,
        OptionFactory $productOptionFactory,
        PurchasedFactory $purchasedFactory,
        CollectionFactory $itemsFactory,
        YesNoMaybe $source,
        array $data = [],
        ?Purchased $purchasedLink = null
    ) {
        $this->source = $source;
        parent::__construct($context, $string, $productOptionFactory, $purchasedFactory, $itemsFactory, $data, $purchasedLink);
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
