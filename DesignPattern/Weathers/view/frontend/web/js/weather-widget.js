define([
    'uiComponent',
    'mage/storage'
], function (Component, storage) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'DesignPattern_Weathers/weather-widget',
            endpoint: '/rest/V1/current-weather/bangkok',
            data: null,
            tracks: {
                data: true
            }
        },

        initialize: function() {
            this._super();
            storage.get(this.endpoint).done(response => this.data = response);
        }
    })
});
