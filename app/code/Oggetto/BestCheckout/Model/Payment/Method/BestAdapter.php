<?php

declare(strict_types=1);

namespace Oggetto\BestCheckout\Model\Payment\Method;

use Magento\Catalog\Model\Product\Configuration\Item\ItemInterface;
use Magento\Payment\Model\Method\Adapter;
use Magento\Quote\Api\Data\CartInterface;
use Oggetto\BestCheckout\Model\Product\Source\IsBest;
use Oggetto\BestCheckout\Setup\Patch\Data\AddIsBestProductAttribute;

class BestAdapter extends Adapter
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
            $isBest = $product->getData(AddIsBestProductAttribute::ATTRIBUTE_CODE);
            if ($isBest === IsBest::VALUE_YES) {
                break;
            }
            if ($isBest !== IsBest::VALUE_MAYBE) {
                return false;
            }
        }

        return parent::isAvailable($quote);
    }
}
