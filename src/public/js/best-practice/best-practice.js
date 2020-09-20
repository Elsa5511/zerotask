function showPreviewImage(imagePath, imageId) {
        if (imagePath !== '') {
            var $previewImageDiv = $("label[for="+imageId+"]")
                                        .parent()
                                        .find(".fileupload-preview.fileupload-exists.thumbnail");
            $previewImageDiv.parent().removeClass('fileupload-new').addClass('fileupload-exists');
            $previewImageDiv.html(
                    '<img src="' +  system.basePath + '/content/best-practice/' + imagePath + '">');
        }
}
$(window).load(function() {
    var newRevisionIsChecked = $("input#new-revision:checked").length > 0;
    if(newRevisionIsChecked) {
        $("#revision-comment").collapse();
    }
    showPreviewImage(featuredImagePath, "featuredImage");
    showPreviewImage(slideOneImagePath, "slide-one");
    showPreviewImage(slideTwoImagePath, "slide-two");
});