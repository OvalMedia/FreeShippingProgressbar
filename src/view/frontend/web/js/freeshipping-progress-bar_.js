define([
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'Magento_Checkout/js/model/totals',
    'jquery',
    'ko'
    ], function (Component, customerData, totals, $, ko) {
        'use strict';

        let cartData = customerData.get('cart');

        return Component.extend({
            defaults: {
                template: 'OM_FreeShippingProgressBar/freeshipping-progress-bar'
            },

            initialize: function () {
                let self = this;
                return this._super();
            },

            getTotal: function() {
                if (totals.totals()) {
                    let subtotal = parseFloat(totals.totals()['subtotal']);
                    return subtotal;
                }
            }
        });
    }
);