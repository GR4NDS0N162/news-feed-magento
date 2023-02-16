<?php

declare(strict_types=1);

namespace Oggetto\ComfortDeliveryTime\Plugin;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Api\ShippingInformationManagementInterface;
use Magento\Quote\Model\Quote\Address;
use Oggetto\ComfortDeliveryTime\Block\Checkout\ComfortDeliveryTime;

class SaveComfortDeliveryTimeInfo
{
    /**
     * @param ShippingInformationManagementInterface $subject
     * @param int                                    $cartId
     * @param ShippingInformationInterface           $addressInformation
     * @return array
     */
    public function beforeSaveAddressInformation(
        ShippingInformationManagementInterface $subject,
        $cartId,
        ShippingInformationInterface $addressInformation,
    ): array {
        /** @var Address $shippingAddress */
        $shippingAddress = $addressInformation->getShippingAddress();
        $comfortDeliveryTime = $shippingAddress->getExtensionAttributes()->getComfortDeliveryTime();
        if ($comfortDeliveryTime) {
            $shippingAddress->setData(ComfortDeliveryTime::ATTRIBUTE_CODE, $comfortDeliveryTime);
        }
        $addressInformation->setShippingAddress($shippingAddress);
        return [$cartId, $addressInformation];
    }
}
