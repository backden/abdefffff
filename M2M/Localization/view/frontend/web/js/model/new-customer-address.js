/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint browser:true jquery:true*/
/*global alert*/
define(['Magento_Customer/js/customer-data', 'underscore'
], function (customerData, _) {
    /**
     * @param {Object} addressData
     * Returns new address object
     */
    return function (addressData) {
        var identifier = Date.now();
        var cityData = customerData.get('directory-city-data'),
            districtData = customerData.get('directory-district-data'),
            regionId = (addressData.region && addressData.region.region_id) ?
                addressData.region.region_id
                : window.checkoutConfig.defaultRegionId, city, district,
            city_id = (addressData.custom_attributes && addressData.custom_attributes.city_id)?addressData.custom_attributes.city_id:addressData.city_id,
            district_id = (addressData.custom_attributes && addressData.custom_attributes.district_id)?addressData.custom_attributes.district_id:addressData.district_id;
            if(addressData.custom_attributes == undefined) {
            addressData.custom_attributes = {};
        }
        if (regionId && cityData()[regionId]
                    && cityData()[regionId][city_id]
                )
        {
            city = cityData()[regionId][city_id];
            addressData.city = city['name'];
        }
        if (city_id
            && districtData()[city_id]
            && districtData()[city_id][district_id]
        )
        {
            district = districtData()[city_id][district_id];
            addressData.district = district['name'];
        }
        addressData.custom_attributes.firstname_katakana = addressData.firstname_katakana;
        addressData.custom_attributes.lastname_katakana = addressData.lastname_katakana;
        addressData.custom_attributes.city_id = city_id;
        addressData.custom_attributes.district_id = district_id;
        addressData.custom_attributes.district = addressData.district;
        if (!_.isEmpty(addressData.custom_attributes.back_id_scan_file) ) {
            addressData.custom_attributes.back_id_scan_file = _.first(addressData.custom_attributes.back_id_scan_file).value;
        }
        else
        {
            addressData.custom_attributes.back_id_scan_file = undefined;
        }
        if (!_.isEmpty(addressData.custom_attributes.front_id_scan_file) ) {
            addressData.custom_attributes.front_id_scan_file = _.first(addressData.custom_attributes.front_id_scan_file).value;
        }
        else
        {
            addressData.custom_attributes.front_id_scan_file = undefined;
        }
        return {
            email: addressData.email,
            countryId: (addressData.country_id) ? addressData.country_id : window.checkoutConfig.defaultCountryId,
            regionId: (addressData.region && addressData.region.region_id) ?
                addressData.region.region_id
                : window.checkoutConfig.defaultRegionId,
            regionCode: (addressData.region) ? addressData.region.region_code : null,
            region: (addressData.region) ? addressData.region.region : null,
            customerId: addressData.customer_id,
            street: addressData.street,
            company: addressData.company,
            telephone: addressData.telephone,
            fax: addressData.fax,
            postcode: addressData.postcode ? addressData.postcode : window.checkoutConfig.defaultPostcode,
            city: addressData.city,
            firstname: addressData.firstname,
            lastname: addressData.lastname,
            middlename: addressData.middlename,
            prefix: addressData.prefix,
            suffix: addressData.suffix,
            vatId: addressData.vat_id,
            saveInAddressBook: addressData.save_in_address_book,
            customAttributes: addressData.custom_attributes,
            isDefaultShipping: function () {
                return addressData.default_shipping;
            },
            isDefaultBilling: function () {
                return addressData.default_billing;
            },
            getType: function () {
                return 'new-customer-address';
            },
            getKey: function () {
                return this.getType();
            },
            getCacheKey: function () {
                return this.getType() + identifier;
            },
            isEditable: function () {
                return true;
            },
            canUseForBilling: function () {
                return true;
            }
        }
    }
});
