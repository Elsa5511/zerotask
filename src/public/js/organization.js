$(document).ready(function () {

    $('.delete-alert .close').click(function (e) {
        $('.delete-alert').hide();
    });
    $('#url').wrap('<div class="input-prepend"></div>').before('<span class="add-on">&nbsp;http://</span>');

    $('.dataTable .btn.button-state').on('click', function (event) {
        event.preventDefault();
        var href = $(this).attr('href');
        var userState = $(this).attr('user-state');
        var modalToShow = null;
        if (userState === 'user-active') {
            modalToShow = $('#confirm-deactivate-user');
        } else if (userState === 'user-deleted') {
            modalToShow = $('#confirm-reactivate-user');
        }
        if (modalToShow) {
            $(modalToShow).find('.btn-danger').off('click').on('click', function (e) {
                e.preventDefault();
                document.location.href = href;
            });
            $(modalToShow).modal('show');
        }
    });


    /**
     * Group Actions > Delete Organizations
     */
    $('#organization-deactivate-all').click(function (e) {
        var selected = new Array();
        $('.data-table-column-check input:checked').each(function () {
            selected.push($(this).val());
        });

        // removing 'on ' values from array
        var removeItem = 'on';
        selected = jQuery.grep(selected, function (value) {
            return value != removeItem;
        });
        var count = selected.length;
        if (count > 0) {
            // Showing the number of organizations to delete
            var newText = $('#count-organizations').text().replace('"x"', count);
            $('#count-organizations').text(newText);

            // put selected values in a hidden input
            $('#deactivate-ids').val(selected);

            // Show a confirm dialog before delete
            $('#confirm-delete-many').modal('show');
        } else {
            $('.delete-alert').show();
        }
    });

});