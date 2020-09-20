$(document).on("datatable-draw", function () {
    var equipmentAttachmentRows = $('.isEquipmentAttachment');

    $(equipmentAttachmentRows).each(function() {
        $(this).find('input[type=checkbox]').remove();

        var aTags = $(this).find('.data-table-column-options > a');
        $(aTags).each(function() {
            var href = $(this).attr('href');
            href = href.replace('equipment-instance-attachment', 'equipment-attachment');
            $(this).attr('href', href);
        });
    });
});
