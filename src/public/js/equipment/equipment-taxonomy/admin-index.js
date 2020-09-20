//$("body").on("datatable-loaded", function() {

$(document).ready(function() {

    $("#deactivate-many-button").click(function() {
        var ids = [];
        $('.data-table-column-check input:checked').each(function () {
            if ($(this).val() > 0) {
                ids.push($(this).val());
            }
        });

        if (ids.length > 0) {
            $("#confirm-deactivate-many-id-value").val(ids);
            $("#confirm-deactivate-many-modal").modal("show");
        } else {
            $('.delete-alert').show();
        }
    });

    $(".activateButton").click(function(event) {
        event.preventDefault();
        id = $(this).attr("href").slice(1); // e.g: "#34".slice(1) = "34"
        $("#confirm-reactivate-id-value").val(id);
        $("#confirm-reactivate-modal").modal("show");
    });

    $(".deactivateButton").click(function(event) {
        event.preventDefault();
        id = $(this).attr("href").slice(1); // e.g: "#34".slice(1) = "34"
        $("#confirm-deactivate-id-value").val(id);
        $("#confirm-deactivate-modal").modal("show");
    });

    $('[data-rel="add"], [data-rel="edit"]').on('click', function(event) {
        event.preventDefault();
        var id = 'add-edit';
        //var formName = $(this).attr('data-form');
        displayModalBox(id, event, 'equipment_taxonomy_form');
    });
});



function displayModalBox(id, event, formName) {
    $.modalbox({
        type: 'iframe',
        id: id,
        title: event.currentTarget.title,
        source: event.currentTarget.href,
        height: "355",
        buttons: ['cancel', {
            'text': system.translations["Save"],
            'class': 'btn btn-primary',
            'on': {
                'click': function() {
                    document.getElementById(id + '-iframe').contentWindow.document.getElementById(formName).submit();
                }
            }
        }]
    });
}