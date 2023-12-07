/*
 * Magento looks for RequireJS configuration files within each module's
 * view/frontend folder named requirejs-config.js.
 *
 * This config object will be injected into require.config() when Magento
 * loops through all of these files. Any specific configuration relating to
 * RequireJS goes within this config object.
 */
var config = {
    "map": {
        "*": {
            "fadeInElement" : "Macademy_JsFun/js/fade-in-element"
        }
    },
    "paths": {
        //"vue": "Macademy_JsFun/js/vue"
        "vue": "https://cdn.jsdelivr.net/npm/vue@2/dist/vue"
    }
};

