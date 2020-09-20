$(document).ready(function() {
    /*$("label[for='main-image']").addClass('required');

    if(pointAttachments) {
        for (var i = 0; i < pointAttachments.length; i++) {
            var fieldsetAttachment = $('form#point_form > fieldset > fieldset > fieldset').get(i);
            var preview = $(fieldsetAttachment).find('.fileupload-preview');
            preview.parent().removeClass('fileupload-new').addClass('fileupload-exists');
            var extension = pointAttachments[i].split('.').pop();
            if(['jpg', 'png', 'gif', 'jpeg'].indexOf(extension) >= 0) {
                var filePath = system.basePath + '/content/' + imagePath + '/' + pointAttachments[i];
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

    if(typeof(image) != 'undefined' && image.length > 0) {
        var fileupload = $('form#point_form > fieldset').find('.fileupload').get(imageIndex);
        var preview = $(fileupload).find('.fileupload-preview');
        preview.parent().removeClass('fileupload-new').addClass('fileupload-exists');
        preview.html(
            '<img src="' + system.basePath + '/content/' + imagePath + '/' + image + '" ' +
            'style="max-height:150px">');
    }*/

    if(pdfIndex >= 0) {
        var fileuploadImageInformation = $('form#point_form > fieldset').find('.fileupload').get(pdfIndex);
        var previewImageInformation = $(fileuploadImageInformation).find('.fileupload-preview');
        if(typeof(imageInformation) != 'undefined' && imageInformation.length > 0) {
            previewImageInformation.parent().removeClass('fileupload-new').addClass('fileupload-exists');
            previewImageInformation.html(imageInformation);
        }
        $(fileuploadImageInformation).find('.thumbnail img').remove();
        $(fileuploadImageInformation).find('.thumbnail').removeAttr('style').addClass('thumbnail-file');
        $(fileuploadImageInformation).find('.btn-file .fileupload-new').html(system.translations['Select file']);
    }

    if (calculationIndex >= 0) {
        var fileuploadCalculationInformation = $('form#point_form > fieldset').find('.fileupload').get(calculationIndex);
        var previewCalculationInformation = $(fileuploadCalculationInformation).find('.fileupload-preview');
        if(typeof(calculationInformation) != 'undefined' && calculationInformation.length > 0) {
            previewCalculationInformation.parent().removeClass('fileupload-new').addClass('fileupload-exists');
            previewCalculationInformation.html(calculationInformation);
        }
        $(fileuploadCalculationInformation).find('.thumbnail img').remove();
        $(fileuploadCalculationInformation).find('.thumbnail').removeAttr('style').addClass('thumbnail-file');
        $(fileuploadCalculationInformation).find('.btn-file .fileupload-new').html(system.translations['Select file']);
    }

    if (attlaIndex >= 0) {
        var fileuploadAttla = $('form#point_form > fieldset').find('.fileupload').get(attlaIndex);
        var previewAttla = $(fileuploadAttla).find('.fileupload-preview');
        if(typeof(attla) != 'undefined' && attla.length > 0) {
            previewAttla.parent().removeClass('fileupload-new').addClass('fileupload-exists');
            previewAttla.html(attla);
        }
        $(fileuploadAttla).find('.thumbnail img').remove();
        $(fileuploadAttla).find('.thumbnail').removeAttr('style').addClass('thumbnail-file');
        $(fileuploadAttla).find('.btn-file .fileupload-new').html(system.translations['Select file']);
    }

    if (railwayCertificateIndex >= 0) {
        var fileuploadRailwayCertificate = $('form#point_form > fieldset').find('.fileupload').get(railwayCertificateIndex);
        var previewRailwayCertificate = $(fileuploadRailwayCertificate).find('.fileupload-preview');
        if(typeof(railwayCertificate) != 'undefined' && railwayCertificate.length > 0) {
            previewRailwayCertificate.parent().removeClass('fileupload-new').addClass('fileupload-exists');
            previewRailwayCertificate.html(railwayCertificate);
        }
        $(fileuploadRailwayCertificate).find('.thumbnail img').remove();
        $(fileuploadRailwayCertificate).find('.thumbnail').removeAttr('style').addClass('thumbnail-file');
        $(fileuploadRailwayCertificate).find('.btn-file .fileupload-new').html(system.translations['Select file']);
    }

    if (railwayTunellProfileIndex >= 0) {
        var fileuploadRailwayTunellProfile = $('form#point_form > fieldset').find('.fileupload').get(railwayTunellProfileIndex);
        var previewRailwayTunellProfile = $(fileuploadRailwayTunellProfile).find('.fileupload-preview');
        if(typeof(railwayTunellProfile) != 'undefined' && railwayTunellProfile.length > 0) {
            previewRailwayTunellProfile.parent().removeClass('fileupload-new').addClass('fileupload-exists');
            previewRailwayTunellProfile.html(railwayTunellProfile);
        }
        $(fileuploadRailwayTunellProfile).find('.thumbnail img').remove();
        $(fileuploadRailwayTunellProfile).find('.thumbnail').removeAttr('style').addClass('thumbnail-file');
        $(fileuploadRailwayTunellProfile).find('.btn-file .fileupload-new').html(system.translations['Select file']);
    }

    if (railwayCalculationIndex >= 0) {
        var fileuploadRailwayCalculation = $('form#point_form > fieldset').find('.fileupload').get(railwayCalculationIndex);
        var previewRailwayCalculation = $(fileuploadRailwayCalculation).find('.fileupload-preview');
        if(typeof(railwayCalculation) != 'undefined' && railwayCalculation.length > 0) {
            previewRailwayCalculation.parent().removeClass('fileupload-new').addClass('fileupload-exists');
            previewRailwayCalculation.html(railwayCalculation);
        }
        $(fileuploadRailwayCalculation).find('.thumbnail img').remove();
        $(fileuploadRailwayCalculation).find('.thumbnail').removeAttr('style').addClass('thumbnail-file');
        $(fileuploadRailwayCalculation).find('.btn-file .fileupload-new').html(system.translations['Select file']);
    }

    if (controlListIndex >= 0) {
        var fileuploadControlList = $('form#point_form > fieldset').find('.fileupload').get(controlListIndex);
        var previewControlList = $(fileuploadControlList).find('.fileupload-preview');
        if(typeof(controlList) != 'undefined' && controlList.length > 0) {
            previewControlList.parent().removeClass('fileupload-new').addClass('fileupload-exists');
            previewControlList.html(controlList);
        }
        $(fileuploadControlList).find('.thumbnail img').remove();
        $(fileuploadControlList).find('.thumbnail').removeAttr('style').addClass('thumbnail-file');
        $(fileuploadControlList).find('.btn-file .fileupload-new').html(system.translations['Select file']);
    }

    /*if(typeof(illustrationImage) != 'undefined' && illustrationImage.length > 0) {
        var fileupload = $('form#point_form > fieldset').find('.fileupload').get(0);
        var preview = $(fileupload).find('.fileupload-preview');
        preview.parent().removeClass('fileupload-new').addClass('fileupload-exists');
        preview.html(
            '<img src="' + system.basePath + '/content/' + imagePath + '/' + illustrationImage + '" ' +
            'style="max-height:150px">');
    }

    if(typeof(errorImage) != 'undefined' && errorImage.length > 0) {
        var fileupload = $('form#point_form > fieldset').find('.fileupload').get(imageIndex);
        $(fileupload).removeClass('fileupload-exists').addClass('fileupload-new');
        var controlGroup = $(fileupload).parents('.control-group').get(0);
        $(controlGroup).addClass('error');
        $(fileupload).after('<span class="help-inline">' + errorImage + '</span>');
        $(fileupload).fileupload();
    }*/

    if(typeof(errorAttla) != 'undefined' && errorAttla.length > 0) {
        var fileupload = $('form#point_form > fieldset').find('.fileupload').get(attlaIndex);
        $(fileupload).removeClass('fileupload-exists').addClass('fileupload-new');
        var controlGroup = $(fileupload).parents('.control-group').get(0);
        $(controlGroup).addClass('error');
        $(fileupload).after('<span class="help-inline">' + errorAttla + '</span>');
        $(fileupload).fileupload();
    }

    if(typeof(errorRailwayTunellProfile) != 'undefined' && errorRailwayTunellProfile.length > 0) {
        var fileupload = $('form#point_form > fieldset').find('.fileupload').get(railwayTunellProfileIndex);
        $(fileupload).removeClass('fileupload-exists').addClass('fileupload-new');
        var controlGroup = $(fileupload).parents('.control-group').get(0);
        $(controlGroup).addClass('error');
        $(fileupload).after('<span class="help-inline">' + errorRailwayTunellProfile + '</span>');
        $(fileupload).fileupload();
    }

    $("#point_form").on("click", "#add-point-btn", function(e) {
        e.preventDefault();
        showAttachmentFieldset();
    });

    $("#point_form").on("click", "#del-point-btn", function(e) {
        e.preventDefault();
        $(this).parents("fieldset").first().remove();
    });

    $('#point_form a[data-dismiss="fileupload"]').on('click', function(event) {
        var target = $(event.target);
        var removedImageInput = target.parents(".control-group").prev();
        removedImageInput.val(1);
    });

    function showAttachmentFieldset() {
        var template =
            '<fieldset>' +
            '<input type="hidden" name="point[' + attachmentsIndex + '][__index__][pointAttachmentId]" value>' +
            '<div class="control-group">' +
            '<label class="control-label required">' + system.translations.Description + '</label>' +
            '<div class="controls"><input name="point[' + attachmentsIndex + '][__index__][description]" required="required" value="" type="text"></div>' +
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