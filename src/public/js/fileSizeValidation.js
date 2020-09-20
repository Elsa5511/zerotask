$(document).ready(function(e) {
    if ($('input[type="file"]').size() > 0) {
        $('input[type="file"]').on('change', function(e) {

            checkFileSize(this);
        });
    }
});

function checkFileSize(inputFile) {
    var max = 1024 * 1024 * 10; // 10MB

    if (typeof(inputFile) !== 'undefined' && inputFile.files && inputFile.files.length > 0) {
        if (inputFile.files[0].size > max) {
            inputFile.value = null; // Clear the field.
            $('.fileupload').fileupload('clear');
            var controlGroup = $(inputFile).closest('.control-group');
            controlGroup.addClass('error');
            controlGroup.find('.help-inline').remove();
            var fileSizeFileErrorMessage = system.translations.maxFileSize;
            $(inputFile).closest('.controls').append('<span class="help-inline">' + fileSizeFileErrorMessage + '</span>');
        }
    }
}
