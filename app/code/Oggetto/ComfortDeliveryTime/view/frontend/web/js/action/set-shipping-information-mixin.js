define(['jquery', 'mage/utils/wrapper', 'Magento_Checkout/js/model/quote'], function ($, wrapper, quote) {
    'use strict';

    return function (setShippingInformationAction) {
        return wrapper.wrap(setShippingInformationAction, function (originalAction) {
            let shippingAddress = quote.shippingAddress();
            if (shippingAddress['extension_attributes'] === undefined) {
                shippingAddress['extension_attributes'] = {};
            }

            const ATTRIBUTE_CODE = 'comfort_delivery_time';

            let attribute = shippingAddress.customAttributes.find(function (element) {
                return element.attribute_code === ATTRIBUTE_CODE;
            });

            shippingAddress['extension_attributes'][ATTRIBUTE_CODE] = attribute.value;

            return originalAction();
        });
    };
});
