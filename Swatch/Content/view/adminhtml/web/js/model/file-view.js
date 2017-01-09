/**
 * Copyright © 2016 Swatch. All rights reserved.
 */
define(
    ['ko'],
    function (ko) {
        'use strict';

        function trimTrailingZeros(number) {
            return number.toFixed(1).replace(/\.0+$/, '');
        }

        function formatFileSize(sizeInBytes) {
            var kiloByte = 1024,
                megaByte = Math.pow(kiloByte, 2),
                gigaByte = Math.pow(kiloByte, 3);

            if (sizeInBytes < kiloByte) {
                return sizeInBytes + ' B';
            }

            if (sizeInBytes < megaByte) {
                return trimTrailingZeros(sizeInBytes / kiloByte) + ' kB';
            }

            if (sizeInBytes < gigaByte) {
                return trimTrailingZeros(sizeInBytes / megaByte) + ' MB';
            }

            return trimTrailingZeros(sizeInBytes / gigaByte) + ' GB';
        }

        return function (file) {
            this.file = file;
            this.uploadProgress = ko.observable(0);
            this.uploadCompleted = ko.observable(false);
            this.uploadSpeedFormatted = ko.observable();
            this.fileName = file.fileName;
            this.fileSizeFormated = formatFileSize(file.fileSize);
        }
    });
