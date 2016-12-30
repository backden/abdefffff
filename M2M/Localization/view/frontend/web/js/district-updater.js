/**
 * @category    frontend Checkout district-updater
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

    $.widget('mage.districtUpdater', {
        options: {
            districtTemplate:
                '<option value="<%- data.value %>" <% if (data.isSelected) { %>selected="selected"<% } %>>' +
                    '<%- data.title %>' +
                '</option>',
            isDistrictRequired: false,
            currentDistrict: null
        },

        _create: function () {
            this._initCityElement();

            this.currentDistrictOption = this.options.currentDistrict;
            this.districtTmpl = mageTemplate(this.options.districtTemplate);

            this._updateDistrict(this.options.currentCity);

            $(this.options.districtListId).on('change', $.proxy(function (e) {
                this.setOption = false;
                this.currentDistrictOption = $(e.target).val();
                $(this.options.districtInputId).val($(e.target).find('option:selected').text());
            }, this));

            $(this.options.districtInputId).on('focusout', $.proxy(function () {
                this.setOption = true;
            }, this));
            $(this.options.countryId).on('change', $.proxy(function () {
                this._updateDistrict(0);
            }, this));
        },

        _initCityElement: function() {
            this.element.on('change', $.proxy(function (e) {
                this._updateDistrict($(e.target).val());
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
         * @param {String} key - district code
         * @param {Object} value - district object
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

                if (this.options.currentDistrict === key) {
                    tmplData.isSelected = true;
                    $(this.options.districtInputId).val(value.name);
                }

                tmpl = this.districtTmpl({
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
                    this.options.districtListId, this.options.districtInputId, this.options.postcodeId);
            }
        },
        /**
         * Update dropdown list based on the city selected
         * @param {String} city - 2 uppercase letter for city code
         * @private
         */
        _updateDistrict: function (city) {
            // Clear validation error messages
            var districtList = $(this.options.districtListId),
                districtInput = $(this.options.districtInputId),
                label = districtList.parent().siblings('label');

            this._clearError();

            // Populate state/province dropdown list if available or use input box
            if (this.options.districtJson[city]) {
                this._removeSelectOptions(districtList);
                $.each(this.options.districtJson[city], $.proxy(function (key, value) {
                    this._renderSelectOption(districtList, key, value);
                }, this));

                if (this.currentDistrictOption && districtList.find('option[value="'+this.currentDistrictOption+'"]').length > 0) {
                    districtList.val(this.currentDistrictOption);
                }

                districtList.removeAttr('disabled').show();
                districtInput.attr('disabled','disabled').hide();
                label.attr('for', districtList.attr('id'));
            } else {
                districtList.attr('disabled','disabled').hide();
                districtInput.removeAttr('disabled').show();
                label.attr('for', districtInput.attr('id'));
            }
        }
    });

    return $.mage.districtUpdater;
});
