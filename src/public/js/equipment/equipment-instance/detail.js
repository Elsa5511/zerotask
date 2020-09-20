$(document).ready(function () {
    $(".delete-visual-control-button").click(function(event) {
        id = $(this).attr("data-id");
        $("#confirm-delete-visual-control-id-value").val(id);
        $("#confirm-delete-visual-control-modal").modal("show");
    });
});
