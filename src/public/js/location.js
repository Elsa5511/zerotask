$(document).ready(function() {

    $('.delete-alert .close').click(function(e) {
        $('.delete-alert').hide();
    });
    $('#url').wrap('<div class="input-prepend"></div>').before('<span class="add-on">&nbsp;http://</span>');


    /**
     * Group Actions > Delete Organizations
     */
    $('a[data-rel="delete-selected"]').click(function(e) {
        var selected = new Array();
        $('.data-table-column-check input:checked').each(function() {
            selected.push($(this).val());
        });

        // removing 'on ' values from array
        var removeItem = 'on';
        selected = jQuery.grep(selected, function(value) {
            return value != removeItem;
        });
        var count = selected.length;
        if (count > 0) {
            // Showing the number of locations to delete
            var newText = $('#count-locations').text().replace('"x"', count);
            $('#count-locations').text(newText);

            // put selected values in a hidden input
            $('#delete-list').val(selected);

            // Show a confirm dialog before delete
            $('#confirm-delete-many').modal('show');
        } else {
            $('.delete-alert').show();
        }
    });

});