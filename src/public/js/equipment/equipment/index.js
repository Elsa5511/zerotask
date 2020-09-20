$(document).ready(function() {
    $('.check-all').on('click', function(event) {
        $(event.target).closest('table').find('input[type=checkbox]').prop('checked', $(event.target).prop('checked'));
    });
    $('#url').wrap('<div class="input-prepend"></div>').before('<span class="add-on">&nbsp;http://</span>');


    $('a[data-rel="deactivate-selected"]').click(function(e) {
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
            $('#deactivate-ids').val(selected);

            // Show a confirm dialog before delete
            $('#confirm-deactivate-many').modal('show');
        } else {
            $('.delete-alert').show();
        }
    });

    $(".delete-category").on('click', function(e) {
        e.preventDefault();
        $('#delete_mode_modal').val($(this).attr('href'));
        $("#confirm-delete").modal('show');
    });
    $('#accept-delete').on('click', function() {

        var url = $('#delete_mode_modal').val();
        location.href = url;

    });
    /**
     * this function open an iframe for editing o adding
     */
    $('a.add-element ,a.edit-element').on('click', function(event) {      
        event.preventDefault();
        var id = 'add-edit';
        var formName = $(this).attr('data-form');
        var dataHeight = $(this).attr('data-height');
        var dataWidth = $(this).attr('data-width');
        displayModalBox(id, event, formName, dataHeight, dataWidth);
    });

    $(".deactivate-equipment-button").click(function(event) {
        event.preventDefault();
        id = $(this).attr("data-id");
        $("#confirm-deactivate-id-value").val(id);
        $("#confirm-deactivate-modal").modal("show");
    });

    $(".reactivate-equipment-button").click(function(event) {
        event.preventDefault();
        id = $(this).attr("data-id");
        $("#confirm-reactivate-id-value").val(id);
        $("#confirm-reactivate-modal").modal("show");
    });
});


/*
 * this function display the modalbox containing a view
 * 
 * @param int id
 * @param object event
 * @param string formName
 * 
 */
function displayModalBox(id, event, formName, dataHeight, dataWidth) {
    var cancelButton = {
        'text': system.translations['Cancel'],
        'class': 'btn',
        'data-dismiss': 'modal'
    };

    var settings = {
        type: 'iframe',
        id: id,
        title: event.currentTarget.title,
        source: event.currentTarget.href,
        height: dataHeight ? dataHeight : "355",
        buttons: [cancelButton, {
            'text': saveButtonName,
            'class': 'btn btn-primary',
            'on': {
                'click': function() {
                    document.getElementById(id + '-iframe').contentWindow.document.getElementById(formName).submit();
                }
            }
        }]
    };
    if(dataWidth)
        settings.width = dataWidth;
    $.modalbox(settings);
}
