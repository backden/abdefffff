/**data) {
        //     this._super(data);
        //     var isVisible = this.visible();
        //     this.required(isVisible);
        //     this.validation = _.omit(this.validation, 'required-entry');
        //     this.validation['required-entry'] = isVisible;
        //     registry.get(this.customName, function (input) {
        //         input.required(!isVisible);
        //         input.validation['required-entry'] = !isVisible;
        //         input.validation['max_text_length'] = 80;
        //
        //     });
        //     return this;
        // },
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
            skipValidation: false,
            imports: {
                update: '${ $.parentName }.city_id:value'
            }
        }
    });
});

