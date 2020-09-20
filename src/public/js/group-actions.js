$(document).ready(function() {    
    /* Before submit confirm form */
    $('.confirm-btn').click(function(e) {
        var form = $(this).parent("form");
        var checkedInstances = $('.data-table-column-check input:checked').not('.check-all');
        checkedInstances.each(function() {
            var inputCloned = '<input name="idList[]" type="hidden" value="' + $(this).val() + '" />';
            form.prepend(inputCloned);
        });
        return true;
    });
    
    /* Hide a warning message */
    $('.delete-alert .close').click(function(e) {
        $('.delete-alert').hide();
    });
});
