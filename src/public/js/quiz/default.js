  jQuery(document).ready(function() {

            
           
            var preview = $('.fileupload-preview.fileupload-exists.thumbnail');
            preview.parent().removeClass('fileupload-new').addClass('fileupload-exists');
            preview.html(
                    '<img src="' + system.basePath + '/content/question/' + imageSrc + '">');
            

        $('a[data-dismiss="fileupload"]').on('click', function() {
            $('input[name="question[remove_image]"]').val(1);
        });
    });