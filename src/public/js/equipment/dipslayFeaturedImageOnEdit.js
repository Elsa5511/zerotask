$(document).ready(function() {
    if (featuredImagePath !== '') {
        var preview = $('.fileupload-preview.fileupload-exists.thumbnail');
        preview.parent().removeClass('fileupload-new').addClass('fileupload-exists');
        preview.html(
                '<img src="' + system.basePath + '/content/equipment/' + featuredImagePath + '">');
    }
    $('form').append('<input id="remove-image" type="hidden" name="remove_image">');
    $('a[data-dismiss="fileupload"]').on('click', function() {
        $('input[name="remove_image"]').val(1);
    });
});