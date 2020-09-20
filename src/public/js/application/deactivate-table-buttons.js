$("body").on("datatable-draw", function () {
    $('.data-table-item').each(function () {
        if ($(this).hasClass('status-active')) {
            $(this).find('.activateButton').css('display', 'none');
        } else if ($(this).hasClass('status-inactive')) {
            $(this).find('.deactivateButton').css('display', 'none');
        }
    });
});
