/* ===========================================================
 * bootstrap-modalbox.js v0.1
 * ===========================================================
 * Copyright 2012 Sysco AS
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================== */
;
(function($) {

    $.fn.modalmanager.defaults.resize = true;

    $.fn.extend({
        modalbox: function(settings) {
            if (settings && typeof(settings) === 'object') {
                settings = $.extend({}, $.modalbox.defaults, settings);
            } else {
                settings = {};
            }

            this.each(function(i, e) {
                $(e).on('click', function() {
                    new $.modalbox(settings, e);
                    return false;
                });
            });

            return;
        }
    });

    $.modalbox = function(settings, e) {
        $('.modalbox-iframe').remove();
        $('body').modalmanager('loading');

        var $mb;

        if (!settings.hasOwnProperty('title') && typeof(e) !== undefined) {
            settings.title = e.title;
        }

        if ($('#' + settings.id).length === 0) {
            $mb = $('<div />', {
                'id': settings.id,
                'class': 'modal hide modalbox-' + settings.type + ' ' + settings.cssClass,
                'tabindex': -1
            }).appendTo('body');


            $mb.prepend('<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h3>' + (settings.title ? settings.title : '&nbsp;') + '</h3></div>');

            $mb.append('<div class="modal-body"></div>');
        } else {
            $mb = $('#' + settings.id);
            $mb.find('.modal-header h3').text(settings.title);
            $mb.find('.modal-body').empty();
            $mb.find('.modal-footer').remove();
        }

        switch (settings.type) {
            case 'ajax':
                $mb.find('.modal-body').load(settings.source, function() {
                    $mb.modal(settings);
                    if (settings.hasOwnProperty('onShow')) {
                        settings.onShow(settings);
                    }
                });
                break;
            case 'iframe':
                if (!settings.hasOwnProperty('source') && typeof(e) !== undefined) {
                    settings.source = e.href;
                }

                if ($('iframe#' + settings.id + '-iframe').length === 0) {
                    var iframe = $('<iframe />', {
                        'id': settings.id + '-iframe',
                        'name': settings.id + '-iframe',
                        'src': settings.source,
                        'height': '100%',
                        'width': '100%',
                        'frameborder': 0
                    });
                } else {
                    iframe = $('iframe[name=' + settings.id + '-iframe' + ']');
                }

                $mb.find('.modal-body').append(iframe);
                $mb.modal(settings);

                iframe = iframe.get(0);

                if (settings.hasOwnProperty('onShow')) {
                    if (navigator.userAgent.indexOf("MSIE") > -1 && !window.opera) {
                        iframe.onreadystatechange = function() {
                            if (iframe.readyState === "complete") {
                                settings.onShow(settings);
                            }
                        };
                    } else {
                        iframe.onload = function() {
                            settings.onShow(settings);
                        };
                    }
                }

                break;
            case 'html':
                $mb.modal(settings);

                $mb.find('.modal-body').html(settings.source);

                if (settings.hasOwnProperty('onShow')) {
                    settings.onShow(settings);
                }
                break;
            case 'inline':
                $mb.find('.modal-body').append($(settings.source).clone());

                $mb.modal(settings);

                if (settings.hasOwnProperty('onShow')) {
                    settings.onShow(settings);
                }
                break;
        }

        if (settings.buttons && settings.buttons.length > 0) {
            $mb.append('<div class="modal-footer"></div>');

            $.each(settings.buttons, function(i, b) {
                if (typeof b === 'string') {
                    switch (b) {
                        case 'ok':
                        case 'yes':
                            b = {
                                'type': 'button',
                                'class': 'btn btn-primary',
                                'text': $.modalbox.locale[b]
                            };
                            break;
                        case 'cancel':
                        case 'no':
                            b = {
                                'type': 'button',
                                'class': 'btn',
                                'text': $.modalbox.locale[b],
                                'data-dismiss': 'modal'
                            };
                            break;
                        case 'close':
                            b = {
                                'type': 'button',
                                'class': 'btn',
                                'text': $.modalbox.locale[b],
                                'data-dismiss': 'modal'
                            };
                            break;
                    }
                }

                $mb.find('.modal-footer').append($('<button />', b));
            });
        }

        if (settings.hasOwnProperty('onHide')) {
            $('#' + settings.id).on('hidden', function() {
                settings.onShow(settings);
            });
        }
        if (typeof(settings.callback) !== 'undefined') {

            $('#' + settings.id+'-iframe').load(function() {
               
                eval(settings.callback + '()');
            });

        }
    };

    $.modalbox.defaults = {
        'resize': true
    };

    $.modalbox.locale = {
        yes: 'Yes',
        no: 'No',
        ok: 'Ok',
        cancel: 'Cancel',
        close: 'Close'
    };

    $.modalbox.uniqid = function() {
        var n = Math.floor(Math.random() * 11);
        var k = Math.floor(Math.random() * 100000000);
        return String.fromCharCode(n) + k;
    };

    $(document).ready(function() {
        $('a[data-toggle=modalbox]').each(function(i, e) {
            var settings = jQuery.parseJSON($(e).attr('data-modalbox-settings'));

            if (!settings.hasOwnProperty('id')) {
                settings.id = 'modalbox-' + $.modalbox.uniqid();
            }

            $(e).modalbox(settings);
        });
    });

})(jQuery);