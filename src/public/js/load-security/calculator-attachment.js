jQuery(document).ready(function() {
    var preview = $('.fileupload-preview.fileupload-exists.thumbnail');
    preview.parent().removeClass('fileupload-new').addClass('fileupload-exists');
    var extension = imageSrc.split('.').pop();
    if(['jpg', 'png', 'gif', 'jpeg'].indexOf(extension) >= 0) {
        var filePath = system.basePath + '/content/load-security/' + imageSrc;
        preview.html('<img src="' + filePath + '" ' + 'style="max-height:150px">');
    } else
        preview.html(imageSrc);

    if(imageError) {
        var fileupload = $('.fileupload').get(0);
        $(fileupload).removeClass('fileupload-exists').addClass('fileupload-new');
        var controlGroup = $(fileupload).parents('.control-group').get(0);
        $(controlGroup).addClass('error');
        $(fileupload).after('<span class="help-inline">' + imageError + '</span>');
    }

    $('a[data-dismiss="fileupload"]').on('click', function() {
        $('input[name="attachment[removed_image]"]').val(1);
    });

    $('form#calculator_attachment span.fileupload-new').text(system.translations["Select document"]);
});
