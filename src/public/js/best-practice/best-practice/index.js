$(document).ready(function() {
    $(".delete-best-practice").on('click', function(e) {
        e.preventDefault();
        $('#delete_mode_modal').val($(this).attr('href'));
        $("#confirm-delete").modal('show');
    });

    $('.delete-alert .close').click(function(e) {
        $('.delete-alert').hide();
    });

    $('#accept-delete').on('click', function() {
        var url = $('#delete_mode_modal').val();
        location.href = url;
    });
});