/**
 * @category    frontend Checkout city-updater
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint browser:true expr:true*/
define([
    'jquery',
    'mage/template',
    'jquery/ui',
    'mage/validation'
], function ($, mageTemplate) {
    'use strict';

    $.widget('mage.cityUpdater', {
        options: {
            cityTemplate:
                '<option value="<%- data.value %>" <% if (data.isSelected) { %>selected="selected"<% } %>>' +
                    '<%- data.title %>' +
                '</option>',
            isCityRequired: true,
            currentCity: null
        },

        _create: function () {
            this._initRegionElement();

            this.currentCityOption = this.options.currentCity;
            this.cityTmpl = mageTemplate(this.options.cityTemplate);

            this._updateCity(this.options.defaultRegion);

            $(this.options.cityListId).on('change', $.proxy(function (e) {
                this.setOption = false;
                this.currentCityOption = $(e.target).val();
                $(this.options.cityInputId).val($(e.target).find('option:selected').text());
            }, this));

            $(this.options.cityInputId).on('focusout', $.proxy(function () {
                this.setOption = true;
            }, this));
            $(this.options.countryId).on('change', $.proxy(function () {
                this.element.val('');
                this._updateCity('');
            }, this));
        },

        _initRegionElement: function() {
            this.element.on('change', $.proxy(function (e) {
                this._updateCity($(e.target).val());
            }, this));
        },

        /**
         * Remove options from dropdown list
         * @param {Object} selectElement - jQuery object for dropdown list
         * @private
         */
        _removeSelectOptions: function (selectElement) {
            selectElement.find('option').each(function (index) {
                if (index) {
                    $(this).remove();
                }
            });
        },

        /**
         * Render dropdown list
         * @param {Object} selectElement - jQuery object for dropdown list
         * @param {String} key - city code
         * @param {Object} value - city object
         * @private
         */
        _renderSelectOption: function (selectElement, key, value) {
            selectElement.append($.proxy(function () {
                var name = value.name.replace(/[!"#$%&'()*+,.\/:;<=>?@[\\\]^`{|}~]/g, '\\$&'),
                    tmplData,
                    tmpl;

                if (value.code && $(name).is('span')) {
                    key = value.code;
                    value.name = $(name).text();
                }

                tmplData = {
                    value: key,
                    title: value.name,
                    isSelected: false
                };

                if (this.options.currentCityOption === key) {
                    tmplData.isSelected = true;
                    $(this.options.cityInputId).val(value.name);
                }

                tmpl = this.cityTmpl({
                    data: tmplData
                });

                return $(tmpl);
            }, this));
        },

        /**
         * Takes clearError callback function as first option
         * If no form is passed as option, look up the closest form and call clearError method.
         * @private
         */
        _clearError: function () {
            if (this.options.clearError && typeof (this.options.clearError) === 'function') {
                this.options.clearError.call(this);
            } else {
                if (!this.options.form) {
                    this.options.form = this.element.closest('form').length ? $(this.element.closest('form')[0]) : null;
                }

                this.options.form = $(this.options.form);

                this.options.form && this.options.form.data('validation') && this.options.form.validation('clearError',
                    this.options.cityListId, this.options.cityInputId, this.options.postcodeId);
            }
        },
        /**
         * Update dropdown list based on the region selected
         * @param {String} region region id
         * @private
         */
        _updateCity: function (region) {
            // Clear validation error messages
            var cityList = $(this.options.cityListId),
                cityInput = $(this.options.cityInputId),
                label = cityList.parent().siblings('label');

            this._clearError();

            // Populate state/province dropdown list if available or use input box
            if (this.options.cityJson[region]) {
                this._removeSelectOptions(cityList);
                $.each(this.options.cityJson[region], $.proxy(function (key, value) {
                    this._renderSelectOption(cityList, key, value);
                }, this));

                if (this.currentCityOption && cityList.find('option[value="'+this.currentCityOption+'"]').length > 0){
                    cityList.val(this.currentCityOption);
                }
                cityList.addClass('required-entry').removeAttr('disabled').show();
                cityInput.removeClass('required-entry').hide();
                label.attr('for', cityList.attr('id'));
                cityList.trigger('change');
            } else {
                cityList.removeClass('required-entry').attr('disabled','disabled').hide();
                cityInput.addClass('required-entry').removeAttr('disabled').show();
                label.attr('for', cityInput.attr('id'));
            }
            //Fixbug for default region
            this.element.val(this.element.val());
            var clearRegion = function () {
                if ($(this.options.regionInputId).is(':hidden') && !this.element.hasClass('required-entry')) {
                    $(this.options.regionInputId).attr('value','');
                }
            }
            setTimeout(clearRegion.bind(this),2000);
        }
    });

    return $.mage.cityUpdater;
});
