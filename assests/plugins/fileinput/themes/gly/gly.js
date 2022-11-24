/*!
 * bootstrap-fileinput v4.3.2
 * http://plugins.krajee.com/file-input
 *
 * Glyphicon (default) theme configuration for bootstrap-fileinput.
 *
 * Author: Kartik Visweswaran
 * Copyright: 2014 - 2016, Kartik Visweswaran, Krajee.com
 *
 * Licensed under the BSD 3-Clause
 * https://github.com/kartik-v/bootstrap-fileinput/blob/master/LICENSE.md
 */
(function ($) {
    "use strict";

    $.fn.fileinputThemes.gly = {
        fileActionSettings: {
            removeIcon: '<i class="fas fa-trash text-danger"></i>',
            uploadIcon: '<i class="fas fa-upload text-info"></i>',
            zoomIcon: '<i class="fas fa-zoom-in"></i>',
            dragIcon: '<i class="fas fa-menu-hamburger"></i>',
            indicatorNew: '<i class="fas fa-hand-down text-warning"></i>',
            indicatorSuccess: '<i class="fas fa-ok-sign text-success"></i>',
            indicatorError: '<i class="fas fa-exclamation-sign text-danger"></i>',
            indicatorLoading: '<i class="fas fa-hand-up text-muted"></i>'
        },
        layoutTemplates: {
            fileIcon: '<i class="fas fa-file kv-caption-icon"></i>'
        },
        previewZoomButtonIcons: {
            prev: '<i class="fas fa-triangle-left"></i>',
            next: '<i class="fas fa-triangle-right"></i>',
            toggleheader: '<i class="fas fa-resize-vertical"></i>',
            fullscreen: '<i class="fas fa-fullscreen"></i>',
            borderless: '<i class="fas fa-resize-full"></i>',
            close: '<i class="fas fa-remove"></i>'
        },
        previewFileIcon: '<i class="fas fa-file"></i>',
        browseIcon: '<i class="fas fa-folder-open"></i>&nbsp;',
        removeIcon: '<i class="fas fa-trash"></i>',
        cancelIcon: '<i class="fas fa-ban-circle"></i>',
        uploadIcon: '<i class="fas fa-upload"></i>',
        msgValidationErrorIcon: '<i class="fas fa-exclamation-sign"></i> '
    };
})(window.jQuery);
