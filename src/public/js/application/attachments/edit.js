$(document).ready(function() {
    $("div.fileupload-new").removeClass("fileupload-new").addClass("fileupload-exists");
    $("span.fileupload-preview").html(filename);

    var $removeAttachmentField = $("#attachment-form input[name=\"attachment_form[removed_attachment]\"]");
    if($removeAttachmentField.size() > 0) {
        $("#attachment-form a[data-dismiss=fileupload]").on("click", function () {
            $removeAttachmentField.val(1);
        });

        if($removeAttachmentField.val() == 1)
            $("#attachment-form a[data-dismiss=fileupload]").click();
    }
});