var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/set-billing-address': {
                'Boostsales_ExtraCheckoutAddressFields/js/action/set-billing-address-mixin': true
            },
            'Magento_Checkout/js/action/set-shipping-information': {
                'Boostsales_ExtraCheckoutAddressFields/js/action/set-shipping-information-mixin': true
            },
            'Magento_Checkout/js/action/create-shipping-address': {
                'Boostsales_ExtraCheckoutAddressFields/js/action/create-shipping-address-mixin': true
            },
            'Magento_Checkout/js/action/place-order': {
                'Boostsales_ExtraCheckoutAddressFields/js/action/set-billing-address-mixin': true
            },
            'Magento_Checkout/js/action/create-billing-address': {
                'Boostsales_ExtraCheckoutAddressFields/js/action/set-billing-address-mixin': true
            }
        }
    }
};