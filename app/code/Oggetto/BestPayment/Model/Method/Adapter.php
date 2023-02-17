<?php

declare(strict_types=1);

namespace Oggetto\BestPayment\Model\Method;

use Magento\Catalog\Model\Product\Configuration\Item\ItemInterface;
use Magento\Payment\Model\Method\Adapter as PaymentAdapter;
use Magento\Quote\Api\Data\CartInterface;
use Oggetto\BestProduct\Model\Config\Source\YesNoMaybe;
use Oggetto\BestProduct\Setup\Patch\Data\IsBestProductPatch;

class Adapter extends PaymentAdapter
{
    /**
     * @inheritDoc
     */
    public function isAvailable(CartInterface $quote = null): bool
    {
        if (!$quote) {
            return false;
        }

        /** @var ItemInterface $item */
        foreach ($quote->getAllItems() as $item) {
            $product = $item->getProduct();
            $isBest = $product->getData(IsBestProductPatch::ATTRIBUTE_CODE) ?? IsBestProductPatch::DEFAULT_VALUE;
            if ($isBest == YesNoMaybe::VALUE_NO) {
                return false;
            }
        }

        return parent::isAvailable($quote);
    }
}
