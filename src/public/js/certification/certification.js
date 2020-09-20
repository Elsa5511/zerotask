$(document).ready(function() {    
    /**
     * Group Actions > Delete
     */
    $('a[data-rel="delete-selected"]').click(function(e) {
        var checkedInstances = $('.data-table-column-check input:checked').not('.check-all');
        var count = checkedInstances.length;
        if (count > 0) {
            $('#confirm-delete-modal .modal-message').text(function(i, txt) {
                return txt.replace(/\d+/, count);
            });
            $('#confirm-delete-modal').modal('show');
        } else {
            $('.delete-alert').show();
        }
    });
});