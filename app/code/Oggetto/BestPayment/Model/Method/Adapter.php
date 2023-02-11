<?php

declare(strict_types=1);

namespace Oggetto\BestPayment\Model\Method;

use Magento\Catalog\Model\Product\Configuration\Item\ItemInterface;
use Magento\Payment\Model\Method\Adapter as PaymentAdapter;
use Magento\Quote\Api\Data\CartInterface;
use Oggetto\BestProduct\Model\Config\Source\YesNoMaybe;
use Oggetto\BestProduct\Setup\Patch\Data\IsBest;

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
            $isBest = $product->getData(IsBest::ATTRIBUTE_CODE);
            if ($isBest !== YesNoMaybe::VALUE_YES && $isBest !== YesNoMaybe::VALUE_MAYBE) {
                return false;
            }
        }

        return parent::isAvailable($quote);
    }
}
