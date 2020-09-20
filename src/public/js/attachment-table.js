$(document).ready(function() {
   $('a[data-rel=attachment-detail-popover]').each(function(index){
        $(this).popover({
            html: true,
            placement: 'right',
            content: $('#details-attachment-' + index).html(),
            title: $('#details-attachment-' + index).attr('data-title')
        })
    });
    $('a[data-rel=attachment-detail-popover]').click(function(e){
        e.preventDefault();
    });
    $('a[data-rel=attachment-detail-popover]').mouseout(function(e){
        $(this).popover('hide');
    });




});

$(document).on("datatable-draw", function () {
    var linkRows = $('.link');

    var linkButtons = linkRows.find("[data-rel=open]");
    linkButtons.each(function (_, button) {
        var linkSplit = $(button).attr("href").split("/");
        var id = linkSplit[linkSplit.length - 1];
        $(button).data("id", id);
        $(button).attr("href", "#");
        $(button).removeAttr("data-rel");
    });


    linkButtons.click(function(event) {
        var id = $(event.target).data("id");
        window.open(linkMap[id]);
    });
});

