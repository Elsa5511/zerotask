$(document).ready(function() {
    $('a.edit_documentation').on('click', function() {
        initTinymce();
    });
});
function initTinymce() {
    tinymce.remove('div.tinymce-content');
    var firstEditorId = $('div.tinymce-content').attr('id');
    tinymce.init({
        selector: "div.tinymce-content",
        auto_focus: firstEditorId,
        inline: true,
        plugins: [
            "advlist autolink lists vidum_link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime table contextmenu paste",
            "save columns moxiemanager"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify columns | bullist numlist outdent indent | link image | save",
        menubar: "edit insert view format table tools",
        extended_valid_elements: "+div[class]",
        save_enablewhendirty: false,
        setup: function(editor) {
            editor.on('SaveContent', function(e) {
                var content = e.content;
                cleaned_content = content.replace(/<p>&nbsp;/g, '<p>');
                e.content = cleaned_content;
            });
        }

    });
}





