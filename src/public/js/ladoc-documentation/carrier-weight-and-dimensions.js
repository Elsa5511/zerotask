$(document).ready(function () {
    var TYPE_OWN_WEIGHT = "own-weight";
    var TYPE_TECHNICAL_WEIGHT = "technical-weight";
    var TYPE_OWN_DIMENSIONS = "own-dimensions";
    var TYPE_LOADING_PLAN_DIMENSIONS = "loading-plan-dimensions";
    var types = [TYPE_OWN_WEIGHT, TYPE_TECHNICAL_WEIGHT, TYPE_OWN_DIMENSIONS, TYPE_LOADING_PLAN_DIMENSIONS];
    var addedAttachmentCount = [];

    wrapInFieldset("weight-additional-info", "");
    wrapInFieldset("dimensions-additional-info", "");

    types.forEach(function(type) {
        addedAttachmentCount[type] = 1;

        addButtonEventListeners(type);

        if (typeof imageFiles !== "undefined" && imageFiles !== null) {
            addUploadedImagesToFields(type);
        }
    });

    function addButtonEventListeners(type) {
        $("#" + type + "-add-attachment").click(function (event) {
            addAttachment(event, type);
        });

        $("#" + type + "-delete-attachment").click(function (event) {
            deleteAttachment(event, type);
        });
    }

    function wrapInFieldset(id, legend) {
        var field = $("#" + id);
        var fieldControl = field.parent().parent();
        var fieldsetId = id + "-fieldset";
        fieldControl.wrap('<fieldset id="' + fieldsetId + '"></fieldset>');
        $("#" + fieldsetId).prepend("<legend>" + legend + "</legend>");
    }

    function addUploadedImagesToFields(type) {
        if (typeof imageFiles[type] !== "undefined" && imageFiles[type] !== null && imageFiles[type].length > 0) {
            addedAttachmentCount[type] = imageFiles[type].length;

            var localFieldset = getAddButton(type).closest("fieldset");
            var previews = $(localFieldset).find('.fileupload-preview');
            previews.each(function (index, elem) {
                preview = $(elem);
                var extension = imageFiles[type][index].split('.').pop();
                if(['jpg', 'png', 'gif', 'jpeg'].indexOf(extension) >= 0) {
                    var filePath = system.basePath + '/content/weight-and-dimensions/' + imageFiles[type][index];
                    preview.html('<img src="' + filePath + '" ' + 'style="max-height:150px">');
                } else
                    preview.html(imageFiles[type][index]);
                preview.parent().removeClass('fileupload-new').addClass('fileupload-exists');
            });
        }
    }

    function getAddButton(type) {
        return $("#" + type + "-add-attachment");
    }

    function addAttachment(event, type) {
        var button = $(event.target);
        var fieldset = button.closest("fieldset");

        var insertAfterPoint = fieldset
            .children("fieldset").eq(0)
            .children("fieldset:last");

        if (insertAfterPoint.length === 0) { // = All attachments deleted
            insertAfterPoint = fieldset
                .children("fieldset").eq(0)
                .children("legend").eq(0);
        }

        var deleteButton = $("#" + type + "-delete-attachment");
        deleteButton.prop("disabled", false);
        addedAttachmentCount[type] += 1;
        var fieldData = {
            form: "weight-and-dimensions",
            fieldSet: convertToFieldDataName(type)
        };
        addAttachmentHtml(insertAfterPoint, fieldData, type);
    }

    function convertToFieldDataName(type) {
        if (type === TYPE_OWN_WEIGHT) {
            return "ownWeight";
        }
        else if (type === TYPE_TECHNICAL_WEIGHT) {
            return "technicalWeight";
        }
        else if (type === TYPE_OWN_DIMENSIONS) {
            return "ownDimensions";
        }
        else if (type === TYPE_LOADING_PLAN_DIMENSIONS) {
            return "loadingPlanDimensions";
        }
    }

    function deleteAttachment(event, type) {
        var button = $(event.target);
        var lastAttachmentFieldset = button
            .closest("fieldset")
            .children("fieldset").eq(0)
            .children("fieldset:last");
        lastAttachmentFieldset.remove();
        addedAttachmentCount[type] -= 1;
        if (addedAttachmentCount[type] === 0) {
            button.prop("disabled", true);
        }
    }

    function addAttachmentHtml(insertAfterPoint, fieldData, type) {
        var inputNamePrefix = fieldData.form + '[' + fieldData.fieldSet + '][attachments][' + (addedAttachmentCount[type] - 1) + ']';
        var fileuploadId = "fileupload-" + type + "-" + (addedAttachmentCount[type] - 1);

        var fileUpload = "fileupload-new";
        var fileuploadPreview = "";

        var template =
            '<fieldset>' +
            '<div class="control-group">' +
            '<input type="hidden" name="' + inputNamePrefix + '[id]" value>' +
            '<input type="hidden" value="0" name="' + inputNamePrefix + '[removed_image]">' +
            '<label class="control-label">' + system.translations.Title + '</label>' +
            '<div class="controls"><input name="' + inputNamePrefix + '[title]"     value="" type="text"></div>' +
            '</div>' +
            '<div class="control-group">' +
            '<label class="control-label" for="attachment_id">' + system.translations.File + '</label>' +
            '<div class="controls">' +
            '<div id="' + fileuploadId + '" class="fileupload ' + fileUpload + '" data-provides="fileupload">' +
            '<input type="hidden" value="" name="' + inputNamePrefix + '[filename]">' +
            '<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">' +
            '<img src="/images/placehold_it.png">' +
            '</div>' +
            '<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;">' +
            fileuploadPreview +
            '</div>' +
            '<div>' +
            '<span class="btn btn-file">' +
            '<span class="fileupload-new">' + system.translations['Select file'] + '</span>' +
            '<span class="fileupload-exists">' + system.translations.Change + '</span>' +
            '<input type="file" name="' + inputNamePrefix + '[filename]">' +
            '</span>' +
            '<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">' + system.translations.Remove + '</a>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</fieldset>';

        $(template).insertAfter(insertAfterPoint);
    }

    $('.alert-error .close').click(function (e) {
        $('.alert-error').hide();
    });

    $('#weight-and-dimensions a[data-dismiss="fileupload"]').on('click', function() {
        var fieldset = $(this).closest('fieldset');
        var removedImageHidden = $(fieldset).find('input.removed_image');
        removedImageHidden.val(1);
    });

    $('form#weight-and-dimensions span.fileupload-new').text(system.translations["Select file"]);
});
