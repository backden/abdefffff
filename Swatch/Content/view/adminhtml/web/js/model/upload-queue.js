/**
 * Copyright © 2016 Swatch. All rights reserved.
 */

define(
    ['ko'],
    function (ko) {
        'use strict';
        return {
            uploads: ko.observableArray([]),
            showTotalProgress: ko.observable(),
            uploadSpeedFormatted:  ko.observable(),
            timeRemainingFormatted: ko.observable(),
            totalProgress: ko.observable()
        }
    });