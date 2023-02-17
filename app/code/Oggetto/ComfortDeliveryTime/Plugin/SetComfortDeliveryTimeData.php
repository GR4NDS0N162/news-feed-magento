<?php

declare(strict_types=1);

namespace Oggetto\ComfortDeliveryTime\Plugin;

use Magento\Quote\Model\Quote\Address as QuoteAddress;
use Magento\Quote\Model\Quote\Address\ToOrderAddress;
use Magento\Sales\Api\Data\OrderAddressInterface;
use Oggetto\ComfortDeliveryTime\Block\Checkout\ComfortDeliveryTime;

class SetComfortDeliveryTimeData
{
    /**
     * @param ToOrderAddress        $subject
     * @param OrderAddressInterface $result
     * @param QuoteAddress          $object
     * @param array                 $data
     * @return OrderAddressInterface
     */
    public function afterConvert(ToOrderAddress $subject, $result, QuoteAddress $object, $data = [])
    {
        $comfortDeliveryTime = $object->getData(ComfortDeliveryTime::ATTRIBUTE_CODE);
        if ($comfortDeliveryTime) {
            $result->setData(ComfortDeliveryTime::ATTRIBUTE_CODE, $comfortDeliveryTime);
        }
        return $result;
    }
}
