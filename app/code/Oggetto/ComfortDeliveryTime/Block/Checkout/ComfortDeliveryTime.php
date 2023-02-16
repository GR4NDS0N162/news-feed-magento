<?php

declare(strict_types=1);

namespace Oggetto\ComfortDeliveryTime\Block\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;

class ComfortDeliveryTime implements LayoutProcessorInterface
{
    public const ATTRIBUTE_CODE = 'comfort_delivery_time';

    /**
     * @inheritDoc
     */
    public function process($jsLayout): array
    {
        $attributeCode = self::ATTRIBUTE_CODE;
        $field = [
            'component'   => 'Magento_Ui/js/form/element/abstract',
            'config'      => [
                'customScope' => 'shippingAddress.custom_attributes',
                'customEntry' => null,
                'template'    => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
            ],
            'dataScope'   => 'shippingAddress.custom_attributes' . '.' . $attributeCode,
            'label'       => 'Comfortable Delivery Time',
            'provider'    => 'checkoutProvider',
            'sortOrder'   => 0,
            'validation'  => [
                'time' => true,
            ],
            'options'     => [],
            'filterBy'    => null,
            'customEntry' => null,
            'visible'     => true,
            'value'       => '',
        ];

        $jsLayout['components']['checkout']['children']['steps']['children']
        ['shipping-step']['children']['shippingAddress']['children']
        ['shipping-address-fieldset']['children'][$attributeCode] = $field;

        return $jsLayout;
    }
}
