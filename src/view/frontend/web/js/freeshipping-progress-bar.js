define([
        'jquery',
        'uiComponent',
        'ko',
        'Magento_Customer/js/customer-data'
    ], function ($, Component, ko, customerData) {
        'use strict';

        //let cartData = ;

        return Component.extend({
            cart: customerData.get('cart'),

            defaults: {
                template: 'OM_FreeShippingProgressBar/freeshipping-progress-bar'
            },

            initialize: function() {
                this._super();
            },

            /**
             *
             * @returns {boolean}
             */
            canShowBlock: function() {
                return (this.getDifference() > 0 ? true : false);
            },

            /**
             *
             * @returns {*}
             */
            getTotal: function() {
                return this.cart().subtotal;
            },

            /**
             *
             * @returns {*}
             */
            getDifference: function() {
                return this.cart().freeshipping_difference;
            },

            /**
             *
             * @returns {*}
             */
            getDifferenceFormatted: function() {
                return this.cart().freeshipping_difference_formatted;
            },

            /**
             *
             * @returns {*}
             */
            getFreeShippingMinValueFormatted: function () {
                return this.cart().freeshipping_min_value_formatted;
            },

            /**
             *
             * @returns {*}
             */
            getPercentComplete: function() {
                return this.cart().freeshipping_percent_complete;
            }
        });
    }
);