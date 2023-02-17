<?php

declare(strict_types=1);

namespace Oggetto\BestProduct\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use Oggetto\BestProduct\Setup\Patch\Data\IsBestProductPatch;

class SaveIsBestAttributeInOrderItem implements ObserverInterface
{
    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        /** @var Order $order */
        $order = $observer->getData('order');

        foreach ($order->getAllItems() as $item) {
            $product = $item->getProduct();
            $isBest = $product?->getData(IsBestProductPatch::ATTRIBUTE_CODE) ?? IsBestProductPatch::DEFAULT_VALUE;
            $item->setData(IsBestProductPatch::ATTRIBUTE_CODE, $isBest);
        }
    }
}
