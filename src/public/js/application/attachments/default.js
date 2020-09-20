/*
 * This file is for open iframe
 */
$(document).ready(function () {
    $('.check-all').on('click', function (event) {
        $(event.target).closest('table').find('input[type=checkbox]').prop('checked', $(event.target).prop('checked'));
    });

    $('a[data-rel=delete-selected]').on('click', function () {
        var elementsCheked = $('.data-table-column-check input:checked').not('.check-all').length;
        if (elementsCheked > 0) {
            $("#delete-many-attachments").modal('show');
        } else {
            $('.page-header').after('<div class="alert">' +
            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
            system.translations.deleteManyAttachmentMessage +
            '</div>');
        }

    });

    var cancelButton = {
        'text': system.translations['Cancel'],
        'class': 'btn',
        'data-dismiss': 'modal'
    };

    $('.add-edit-attachment').on('click', function (e) {
        e.preventDefault();
        var href = $(this).attr("href");

        $.modalbox({
            type: 'iframe',
            id: "iframeId",
            title: e.currentTarget.title,
            source: href,
            height: "355",
            buttons: [cancelButton, {
                'text': saveButtonName,
                'class': 'btn btn-primary',
                'on': {
                    'click': function () {
                        document.getElementById("iframeId-iframe").contentWindow.document.getElementById("attachment-form").submit();

                    }
                }
            }]
        });
    });

    $('a[data-rel=edit-attachment]').on('click', function (e) {
        e.preventDefault();

        $.modalbox({
            type: 'iframe',
            id: 'edit-attachment',
            title: e.currentTarget.title,
            source: e.currentTarget.href,
            height: "355",
            buttons: [cancelButton, {
                'text': saveButtonName,
                'class': 'btn btn-primary',
                'on': {
                    'click': function () {
                        document.getElementById('edit-attachment-iframe').contentWindow.document.getElementById("attachment_form_attachment").submit();
                    }
                }
            }]
        });
    });

    $('a[data-rel=edit-category]').on('click', function (e) {
        e.preventDefault();

        $.modalbox({
            type: 'iframe',
            id: 'edit-attachment_taxonomy',
            title: e.currentTarget.title,
            source: e.currentTarget.href,
            height: "355",
            buttons: [cancelButton, {
                'text': saveButtonName,
                'class': 'btn btn-primary',
                'on': {
                    'click': function () {
                        document.getElementById('edit-attachment_taxonomy-iframe').contentWindow.document.getElementById("attachment_taxonomy_form").submit();
                    }
                }
            }]
        });
    });

    $(".delete-category").on('click', function (e) {
        e.preventDefault();
        $('#delete_mode_modal').val($(this).attr('href'));
        $("#confirm-delete").modal('show');
    });
    $('#accept-delete').on('click', function () {

        var url = $('#delete_mode_modal').val();
        location.href = url;
    });
});

