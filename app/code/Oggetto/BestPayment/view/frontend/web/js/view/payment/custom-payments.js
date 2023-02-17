define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
], function (Component, rendererList) {
    'use strict';

    rendererList.push(
        {
            type: 'best', // must equals the payment code
            component: 'Oggetto_BestPayment/js/view/payment/method-renderer/purchaseorder-method'
        }
    );

    /** Add view logic here if you needed */
    return Component.extend({});
});
