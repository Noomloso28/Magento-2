define([
    'uiComponent',
    ],function (
        Component
){
    'use strict';


    return Component.extend({
        defaults: {
           /* message : 'Free shipping Banner UI component has been loaded message 2+1.', */
            subtotal : 323.00,
            template: 'Macademy_FreeShippingPromo/free-shipping-banner'
        },
        initialize : function (){

            this._super();
            console.log( this.message );
        },
        formatCurrency: function(value) {
            return '$' + value.toFixed(2);
        }
    });
});
