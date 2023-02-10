define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
], function (Component, rendererList) {
    'use strict';

    rendererList.push(
        {
            type: 'custompurchaseorder', // must equals the payment code
            component: 'Jesadiya_PurchaseOrderPayment/js/view/payment/method-renderer/purchaseorder-method'
        }
    );

    /** Add view logic here if you needed */
    return Component.extend({});
});
