/**
 * Copyright © 2016 Swatch. All rights reserved.
 */

/**
 * Copyright © 2016 Swatch. All rights reserved.
 */
define(['jquery', 'uiComponent', 'ko', 'Swatch_Content/js/model/upload-queue'], function ($, Component, ko, uploadsModel) {
    'use strict';

    return Component.extend({
            uploads: uploadsModel.uploads,
            showTotalProgress: uploadsModel.showTotalProgress,
            uploadSpeedFormatted:  uploadsModel.uploadSpeedFormatted,
            timeRemainingFormatted: uploadsModel.timeRemainingFormatted,
            totalProgress:  uploadsModel.totalProgress
        }
    )
});