<?php

declare(strict_types=1);

namespace Oggetto\ComfortDeliveryTime\Plugin;

use Magento\Sales\Block\Adminhtml\Order\View\Info;
use Magento\Sales\Model\Order\Address;
use Oggetto\ComfortDeliveryTime\Block\Checkout\ComfortDeliveryTime;

class ShowComfortDeliveryTimeInfo
{
    /**
     * @param Info        $subject
     * @param string|null $result
     * @param Address     $address
     * @return string|null
     */
    public function afterGetFormattedAddress(Info $subject, $result, Address $address)
    {
        if ($address->getAddressType() !== Address::TYPE_SHIPPING) {
            return $result;
        }
        $comfortDeliveryTime = $address->getData(ComfortDeliveryTime::ATTRIBUTE_CODE) ?: 'Not Stated';
        $result .= "<br />Comfortable Delivery Time: {$comfortDeliveryTime}";
        return $result;
    }
}
