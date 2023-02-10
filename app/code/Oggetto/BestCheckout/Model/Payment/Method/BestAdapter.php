<?php

declare(strict_types=1);

namespace Oggetto\BestCheckout\Model\Payment\Method;

use Magento\Payment\Model\Method\Adapter;
use Magento\Quote\Api\Data\CartInterface;
use Oggetto\BestCheckout\Model\Config\Source\YesNoMaybe;
use Oggetto\BestCheckout\Setup\Patch\Data\IsBest;

class BestAdapter extends Adapter
{
    /**
     * @inheritDoc
     */
    public function isAvailable(CartInterface $quote = null)
    {
        if (!$quote) {
            return false;
        }

        foreach ($quote->getAllItems() as $item) {
            $isBest = $item->getProduct()->getData(IsBest::ATTRIBUTE_CODE);
            if ($isBest !== YesNoMaybe::VALUE_YES && $isBest !== YesNoMaybe::VALUE_MAYBE) {
                return false;
            }
        }

        return parent::isAvailable($quote);
    }
}
