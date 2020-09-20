$(document).ready(function() {

    $('.table-users tr.data-table-item').each(function(){
        var classUserActive = 'user-active', classUserDeleted = 'user-deleted';
        if($(this).hasClass(classUserActive)){
            $(this).find('.button-state[user-state="' + classUserDeleted + '"]').css('display', 'none');
        } else if($(this).hasClass(classUserDeleted)) {
            $(this).find('.button-state[user-state="' + classUserActive + '"]').css('display', 'none');
        }
    });
    
    $('.table-users .btn.button-state').on('click', function(event){
        event.preventDefault();
        var href = $(this).attr('href');
        var userState = $(this).attr('user-state');
        var modalToShow = null;
        if(userState === 'user-active'){
            modalToShow = $('#confirm-deactivate-user');
        } else if(userState === 'user-deleted') {
            modalToShow = $('#confirm-reactivate-user');
        }
        if(modalToShow){
            $(modalToShow).find('.btn-danger').off('click').on('click', function(e){
                e.preventDefault();
                document.location.href = href;
            });
            $(modalToShow).modal('show');
        }
    });

    $('.delete-alert .close').click(function(e) {
        $('.delete-alert').hide();
    });

    // Cleanest way to detect all changes to input field.
    setInterval(function() {
        passwordFieldHasContent = ($("#password").val() !== '');
        $("#oldPassword").prop("required", passwordFieldHasContent);
        $("#verify-password").prop("disabled", !passwordFieldHasContent);
    }, 100);

    /**
     * Group Actions > Delete Users
     */
    $('#user-delete-selected').click(function(e) {
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
            // Showing the number of users to delete
            var newText = $('#count-users').text().replace('"x"', count);
            $('#count-users').text(newText);

            // put selected values in a hidden input
            $('#delete-list').val(selected);

            // Show a confirm dialog before delete
            $('#confirm-delete-many').modal('show');
        } else {
            $('.delete-alert').show();
        }
    });

    /**
     * Show a confirm dialog to cancel from a user form
     */
    $("form").on("click", "#user-btn-cancel", function(e) {
        e.preventDefault();
        $('#confirm-cancel').modal('show');
    });
});