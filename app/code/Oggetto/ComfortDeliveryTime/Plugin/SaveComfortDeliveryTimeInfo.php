<?php

declare(strict_types=1);

namespace Oggetto\ComfortDeliveryTime\Plugin;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Api\ShippingInformationManagementInterface;

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
        return [$cartId, $addressInformation];
    }
}
