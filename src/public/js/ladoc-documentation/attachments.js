$(document).ready(function() {

    if(pointAttachments) {
        for(var i = 0; i < pointAttachments.length; i++) {
            var fieldsetAttachment = $('form#point_form > fieldset > fieldset > fieldset').get(i);
            var preview = $(fieldsetAttachment).find('.fileupload-preview');
            preview.parent().removeClass('fileupload-new').addClass('fileupload-exists');
            var extension = pointAttachments[i].split('.').pop();
            if(['jpg', 'png', 'gif', 'jpeg'].indexOf(extension) >= 0) {
                var filePath = system.basePath + '/content/' + controllerName + '/' + pointAttachments[i];
                preview.html('<img src="' + filePath + '" ' + 'style="max-height:150px">');
            } else
                preview.html(pointAttachments[i]);
        }
    }

    if(errorAttachments) {
        var attachmentsFieldset = $('form#point_form > fieldset > fieldset');
        for(var k in errorAttachments) {
            var filename = $(attachmentsFieldset).find('input[name="point[' + attachmentsIndex + '][' + k + '][filename]"]');
            var controlGroup = $(filename).parents('.control-group').get(0);
            var fileupload = $(controlGroup).find('.fileupload').get(0);
            $(fileupload).removeClass('fileupload-exists').addClass('fileupload-new');
            $(controlGroup).addClass('error');
            $(fileupload).after('<span class="help-inline">' + errorAttachments[k] + '</span>');
        }

        if(newAttachments) {
            for(var k in newAttachments) {
                var filename = $(attachmentsFieldset).find('input[name="point[' + attachmentsIndex + '][' + k + '][filename]"]');
                var controlGroup = $(filename).parents('.control-group').get(0);
                var fileupload = $(controlGroup).find('.fileupload').get(0);
                $(fileupload).removeClass('fileupload-exists').addClass('fileupload-new');
            }
        }
    }

    $("#point_form").on("click", "#add-point-btn", function(e) {
        e.preventDefault();
        showAttachmentFieldset();
    });

    $("#point_form").on("click", "#del-point-btn", function(e) {
        e.preventDefault();
        $(this).parents("fieldset").first().remove();
    });

    $('#point_form a[data-dismiss="fileupload"]').on('click', function() {
        var fieldset = $(this).parents('fieldset').get(0);
        $(fieldset).find('input.removed_image').val(1);
    });

    function showAttachmentFieldset() {
        var template =
         '<fieldset>' +
            '<input type="hidden" name="point[' + attachmentsIndex + '][__index__][pointAttachmentId]" value>' +
            '<div class="control-group">' +
               '<label class="control-label required">' + system.translations.Title + '</label>' +
               '<div class="controls"><input name="point[' + attachmentsIndex + '][__index__][title]" required="required" value="" type="text"></div>' +
            '</div>' +
            '<div class="control-group">' +
                '<label class="control-label required" for="attachment_id">' + system.translations.File + '</label>' +
                '<div class="controls">' +
                    '<div class="fileupload fileupload-new" data-provides="fileupload">' +
                        '<input type="hidden" value="" name="point[' + attachmentsIndex + '][__index__][filename]">' +
                        '<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">' +
                            '<img src="/images/placehold_it.png">' +
                        '</div>' +
                        '<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;">' +
                        '</div>' +
                        '<div>' +
                            '<span class="btn btn-file">' +
                                '<span class="fileupload-new">' + system.translations['Select file'] + '</span>' +
                                '<span class="fileupload-exists">' + system.translations.Change + '</span>' +
                                '<input type="file" name="point[' + attachmentsIndex + '][__index__][filename]">' +
                            '</span>' +
                            '<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">' + system.translations.Remove + '</a>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>' +
            '<div class="control-group">' +
                '<div class="controls">' +
                    '<input type="submit" name="point[' + attachmentsIndex + '][0][delete]" id="del-point-btn" class="btn btn-primary btn-default" value="' + system.translations.deleteAttachment + '">' +
                '</div>' +
            '</div>' +
        '</fieldset>';

        var currentCount = $('form#point_form > fieldset > fieldset > fieldset').length;
        template = template.replace(/__index__/g, currentCount);

        $('form#point_form > fieldset > fieldset').append(template);
        $('form#point_form > fieldset > fieldset > fieldset:last .fileupload').fileupload();

         return false;
    }
    
    $('.alert-error .close').click(function(e) {
        $('.alert-error').hide();
    });
});