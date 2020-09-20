$(document).ready(function() {

    $('#deactivate-many-button').click(function (e) {
        var checkedInstances = $('.data-table-column-check input:checked').not('.check-all');

        var count = checkedInstances.length;
        if (count > 0) {
            $('#confirm-deactivate-modal .modal-message').text(function (i, txt) {
                return txt.replace(/\d+/, count);
            });
            $('#confirm-deactivate-modal').modal('show');

        } else {
            $('.delete-alert').show();
        }
    });

});