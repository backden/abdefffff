define([
    'underscore',
    'uiComponent',
    'uiRegistry',
    'jquery',
    'Magento_Customer/js/customer-data'
    ],
    function (_, Component, registry, $, customerData) {
        'use strict';

        var parentScope = 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.',
            attributes = [
                'region_id',
                'city_id',
                'district_id'
            ],
            checkoutButton = 'button[data-action="checkout-method-guest"]',
            newAddressButton = '#checkout-step-shipping button.action-show-popup',
            currentAddressSelectedBtn;

        $(document).on('click touch', checkoutButton , function () {
            alignFields();
        });
        $(document).on('click touch',newAddressButton, function () {
            alignFields();
            currentAddressSelectedBtn = $('.shipping-address-item.selected-item').children('button');
        });
        $(document).on('click touch','.action-hide-popup',function () {
            if (currentAddressSelectedBtn) {
                currentAddressSelectedBtn.click();
            }
        });
        function alignFields()
        {
            $.each(attributes, function (index, name) {
                registry.get(parentScope+name, function (item) {
                    var customEntry = registry.get(item.customName);
                    if(customEntry)
                    {
                        customEntry.visible(!item.visible());
                        var itemEl = $('[name="' + item.parentScope+'.'+item.inputName + '"]');
                        var customEl = $('[name="' + customEntry.parentScope+'.'+customEntry.inputName + '"]');
                        itemEl.after(customEl);
                    }
                })
            });
        }
        return Component.extend({
            initialize: function () {
                this._super();
                this.source.on('shippingAddress', function (value) {
                    if (!this.isFixed) {
                        setTimeout(alignFields(), 4000);
                        this.isFixed = true;
                    }

                }.bind(this));
                var cityData, districtData;
                cityData = customerData.get('directory-city-data');
                if (_.isEmpty(cityData())) {
                    customerData.reload(['directory-city-data'], false);
                }
                districtData = customerData.get('directory-district-data');
                if (_.isEmpty(districtData())) {
                    customerData.reload(['directory-district-data'], false);
                }
                window.alignFields = alignFields;
            },
            isFixed: false
        });
    }
);