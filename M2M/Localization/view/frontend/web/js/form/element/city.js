/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/select'
], function (_, registry, Select) {
    'use strict';
    return Select.extend({
        defaults: {
            imports: {
                update: '${ $.parentName }.region_id:value'
            },
            required: true,
        },

        /**
         * Extends instance with defaults, extends config with formatted values
         *     and options, and invokes initialize method of AbstractElement class.
         *     If instance's 'customEntry' property is set to true, calls 'initInput'
         */
        initialize: function () {
            this._super();

            return this;
        },

        setOptions: function (data) {
            this._super(data);
            var isVisible = this.visible();
            this.required(isVisible);
            this.validation = _.omit(this.validation, 'required-entry');
            this.validation['required-entry'] = isVisible;
            registry.get(this.customName, function (input) {
                input.required(!isVisible);
                input.validation['required-entry'] = !isVisible;
                input.validation['max_text_length'] = 80;

            });
            return this;
        },
        update: function (value) {
            this.validation = _.omit(this.validation, 'required-entry');
            var isVisible = this.visible();
            this.required(isVisible);
            this.validation['required-entry'] = isVisible;
            registry.get(this.customName, function (input) {
                input.required(!isVisible);
                input.validation['required-entry'] = !isVisible;
                input.validation['max_text_length'] = 80;

            });
        },

        /**
         * Filters 'initialOptions' property by 'field' and 'value' passed,
         * calls 'setOptions' passing the result to it
         *
         * @param {*} value
         * @param {String} field
         */
        filter: function (value, field) {
            this._super(value, field);
        }
    });
});

