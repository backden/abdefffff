define([
    "jquery",
    "prototype",
    "mage/adminhtml/events",
    "mage/adminhtml/form"
], function(jQuery){
    CityUpdater = Class.create();
    CityUpdater.prototype = {
        initialize: function (regionEl, cityTextEl, citySelectEl, cities, disableAction, clearCityValueOnDisable)
        {
            this.regionEl = $(regionEl)
            this.cityTextEl = $(cityTextEl);
            this.citySelectEl = $(citySelectEl);
            this.config = cities['config'];
            delete cities.config;
            this.cities = cities;
            this.disableAction = (typeof disableAction=='undefined') ? 'hide' : disableAction;
            this.clearCityValueOnDisable = (typeof clearCityValueOnDisable == 'undefined') ? false : clearCityValueOnDisable;

            if (this.citySelectEl.options.length<=1) {
                this.update();
            }
            else {
                this.lastRegionId = this.regionEl.value;
            }

            this.regionEl.changeUpdater = this.update.bind(this);
            this._addRequiredClass(this.citySelectEl);
            Event.observe(this.regionEl, 'change', this.update.bind(this));
            Event.observe(this.citySelectEl, 'change', this._syncValue.bind(this));
            varienGlobalEvents.attachEventHandler("address_country_changed", this.update.bind(this));
            varienGlobalEvents.attachEventHandler("formSubmit",this._syncValue.bind(this))
        },
        _syncValue: function (formId) {
            if (!this.citySelectEl.disabled && jQuery(this.citySelectEl).val() != '') {
                jQuery(this.cityTextEl).val(jQuery(this.citySelectEl.selectedOptions).attr('title'));
            }
            else {
                this.citySelectEl.value = '';
            }
        }
        ,
        _addRequiredClass: function(selectEl){
            if(selectEl.parentNode.parentNode.hasClassName('_required')){
                if (selectEl.style.display != "none") {
                    selectEl.addClassName('required-entry');
                }
                else {
                    selectEl.removeClassName('required-entry');
                    if($(selectEl.id+'-error') != undefined)
                    {
                        $(selectEl.id+'-error').remove();
                    }
                }
                var textEl = $(selectEl.id.replace('_id',''));
                if (textEl.style.display != "none") {
                    textEl.addClassName('required-entry');
                }
                else {
                    textEl.removeClassName('required-entry');
                    if($(textEl.id+'-error') != undefined)
                    {
                        $(textEl.id+'-error').remove();
                    }
                }
            }
        },
        update: function()
        {
            if(this.regionEl.disabled)
            {
                this.regionEl.value = '';
            }
            if (this.cities[this.regionEl.value]) {

                if (this.lastRegionId!=this.regionEl.value) {
                    var i, option, city, def;

                    def = this.citySelectEl.getAttribute('defaultValue');
                    if (this.cityTextEl) {
                        if (!def) {
                            def = this.cityTextEl.value.toLowerCase();
                        }
                        this.cityTextEl.value = '';
                    }

                    this.citySelectEl.options.length = 1;
                    for (cityId in this.cities[this.regionEl.value]) {
                        city = this.cities[this.regionEl.value][cityId];

                        option = document.createElement('OPTION');
                        option.value = cityId;
                        option.text = city.name.stripTags();
                        option.title = city.name;

                        if (this.citySelectEl.options.add) {
                            this.citySelectEl.options.add(option);
                        } else {
                            this.citySelectEl.appendChild(option);
                        }

                        if (cityId==def || city.name.toLowerCase()==def || city.code.toLowerCase()==def) {
                            this.citySelectEl.value = cityId;
                        }
                    }
                }

                if (this.disableAction=='hide') {
                    if (this.cityTextEl) {
                        this.cityTextEl.style.display = 'none';
                        this.cityTextEl.style.disabled = true;
                    }
                    this.citySelectEl.style.display = '';
                    this.citySelectEl.disabled = false;
                } else if (this.disableAction=='disable') {
                    if (this.cityTextEl) {
                        this.cityTextEl.disabled = true;
                    }
                    this.citySelectEl.disabled = false;
                }
                this.setMarkDisplay(this.citySelectEl, true);

                this.lastRegionId = this.regionEl.value;
            } else {
                if (this.disableAction=='hide') {
                    if (this.cityTextEl) {
                        this.cityTextEl.style.display = '';
                        this.cityTextEl.style.disabled = false;
                    }
                    this.citySelectEl.value = '';
                    this.citySelectEl.style.display = 'none';
                    this.citySelectEl.disabled = true;
                } else if (this.disableAction=='disable') {
                    if (this.cityTextEl) {
                        this.cityTextEl.disabled = false;
                    }
                    this.citySelectEl.disabled = true;
                    if (this.clearCityValueOnDisable) {
                        this.citySelectEl.value = '';
                    }
                } else if (this.disableAction=='nullify') {
                    this.citySelectEl.options.length = 1;
                    this.citySelectEl.value = '';
                    this.citySelectEl.selectedIndex = 0;
                    this.lastRegionId = '';
                }
                this.setMarkDisplay(this.citySelectEl, false);
            }
            varienGlobalEvents.fireEvent("address_city_changed", this.regionEl);
            this._addRequiredClass(this.citySelectEl);
        },

        setMarkDisplay: function(elem, display){
            if(elem.parentNode.parentNode){
                var marks = Element.select(elem.parentNode.parentNode, '.required');
                if(marks[0]){
                    display ? marks[0].show() : marks[0].hide();
                }
            }
        }
    };

    cityUpdater = CityUpdater;

    DistrictUpdater = Class.create();
    DistrictUpdater.prototype = {
        initialize: function (cityEl, districtTextEl, districtSelectEl, districts, disableAction, clearDistrictValueOnDisable)
        {
            this.cityEl = $(cityEl);
            this.districtTextEl = $(districtTextEl);
            this.districtSelectEl = $(districtSelectEl);
            this.districts = districts;
            this.isDistrictRequired = true;
            this.disableAction = (typeof disableAction=='undefined') ? 'hide' : disableAction;
            this.clearDistrictValueOnDisable = (typeof clearDistrictValueOnDisable == 'undefined') ? false : clearDistrictValueOnDisable;

            if (this.districtSelectEl.options.length<=1) {
                this.update();
            }
            else {
                this.lastCityId = this.cityEl.value;
            }

            this.cityEl.changeUpdater = this.update.bind(this);
            Event.observe(this.cityEl, 'change', this.update.bind(this));
            Event.observe(this.districtSelectEl, 'change', this._syncValue.bind(this));
            varienGlobalEvents.attachEventHandler("address_city_changed", this.update.bind(this));
            varienGlobalEvents.attachEventHandler("formSubmit",this._syncValue.bind(this))
        },
        _syncValue: function (formId) {
            if (!this.districtSelectEl.disabled && jQuery(this.districtSelectEl).val() != '') {
                jQuery(this.districtTextEl).val(jQuery(this.districtSelectEl.selectedOptions).attr('title'));
            }
            else
            {
                this.districtSelectEl.value = '';
            }
        },
        _checkDistrictRequired: function()
        {
            if (!this.isDistrictRequired) {
                return;
            }

            var label, wildCard;
            var elements = [this.districtTextEl, this.districtSelectEl];
            var that = this;
            var districtRequired = this.districts[this.cityEl.value] != undefined;
            districtRequired?this.districtSelectEl.parentNode.parentNode.addClassName('_required'):this.districtSelectEl.parentNode.parentNode.removeClassName('_required');

            elements.each(function(currentElement) {
                if(!currentElement) {
                    return;
                }
                var form = currentElement.form,
                    validationInstance = form ? jQuery(form).data('validation') : null,
                    field = currentElement.up('.field') || new Element('div');

                if (validationInstance) {
                    validationInstance.clearError(currentElement);
                }
                label = $$('label[for="' + currentElement.id + '"]')[0];
                if (label) {
                    wildCard = label.down('em') || label.down('span.required');
                    var topElement = label.up('tr') || label.up('li');
                    if (topElement) {
                        if (districtRequired) {
                            topElement.show();
                        } else {
                            topElement.hide();
                        }
                    }
                }

                if (label && wildCard) {
                    if (!districtRequired) {
                        wildCard.hide();
                    } else {
                        wildCard.show();
                    }
                }

                //compute the need for the required fields
                if (!districtRequired || !currentElement.visible()) {
                    if (field.hasClassName('required')) {
                        field.removeClassName('required');
                    }
                    if (currentElement.hasClassName('required-entry')) {
                        currentElement.removeClassName('required-entry');
                    }
                    if ('select' == currentElement.tagName.toLowerCase() &&
                        currentElement.hasClassName('validate-select')
                    ) {
                        currentElement.removeClassName('validate-select');
                    }
                } else {
                    if (!field.hasClassName('required')) {
                        field.addClassName('required');
                    }
                    if (!currentElement.hasClassName('required-entry')) {
                        currentElement.addClassName('required-entry');
                    }
                    if ('select' == currentElement.tagName.toLowerCase() &&
                        !currentElement.hasClassName('validate-select')
                    ) {
                        currentElement.addClassName('validate-select');
                    }
                }
            });
        },

        disableDistrictValidation: function()
        {
            this.isDistrictRequired = false;
        },

        update: function()
        {
            if (this.districts[this.cityEl.value]) {
                if (this.lastCityId!=this.cityEl.value) {
                    var i, option, district, def;

                    def = this.districtSelectEl.getAttribute('defaultValue');
                    if (this.districtTextEl) {
                        if (!def) {
                            def = this.districtTextEl.value.toLowerCase();
                        }
                        this.districtTextEl.value = '';
                    }

                    this.districtSelectEl.options.length = 1;
                    for (districtId in this.districts[this.cityEl.value]) {
                        district = this.districts[this.cityEl.value][districtId];

                        option = document.createElement('OPTION');
                        option.value = districtId;
                        option.text = district.name.stripTags();
                        option.title = district.name;

                        if (this.districtSelectEl.options.add) {
                            this.districtSelectEl.options.add(option);
                        } else {
                            this.districtSelectEl.appendChild(option);
                        }

                        if (districtId==def || district.name.toLowerCase()==def || district.code.toLowerCase()==def) {
                            this.districtSelectEl.value = districtId;
                        }
                    }
                }

                if (this.disableAction=='hide') {
                    if (this.districtTextEl) {
                        this.districtTextEl.style.display = 'none';
                        this.districtTextEl.style.disabled = true;
                    }
                    this.districtSelectEl.style.display = '';
                    this.districtSelectEl.disabled = false;
                } else if (this.disableAction=='disable') {
                    if (this.districtTextEl) {
                        this.districtTextEl.disabled = true;
                    }
                    this.districtSelectEl.disabled = false;
                }
                this.setMarkDisplay(this.districtSelectEl, true);
                this.lastCityId = this.cityEl.value;
            } else {
                if (this.disableAction=='hide') {
                    if (this.districtTextEl) {
                        this.districtTextEl.style.display = '';
                        this.districtTextEl.style.disabled = false;
                    }
                    this.districtSelectEl.style.display = 'none';
                    this.districtSelectEl.disabled = true;
                } else if (this.disableAction=='disable') {
                    if (this.districtTextEl) {
                        this.districtTextEl.disabled = false;
                    }
                    this.districtSelectEl.disabled = true;
                    if (this.clearDistrictValueOnDisable) {
                        this.districtSelectEl.value = '';
                    }
                } else if (this.disableAction=='nullify') {
                    this.districtSelectEl.options.length = 1;
                    this.districtSelectEl.value = '';
                    this.districtSelectEl.selectedIndex = 0;
                    this.lastCityId = '';
                }
                this.setMarkDisplay(this.districtSelectEl, false);
            }
            varienGlobalEvents.fireEvent("address_district_changed", this.cityEl);
            this._checkDistrictRequired();
        },

        setMarkDisplay: function(elem, display){
            if(elem.parentNode.parentNode){
                var marks = Element.select(elem.parentNode.parentNode, '.required');
                if(marks[0]){
                    display ? marks[0].show() : marks[0].hide();
                }
            }
        }
    };
    districtUpdater = DistrictUpdater;
});