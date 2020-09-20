function GetFirstEquipment(validateByEquipment) {
    var compareEquipmentType = '';
    var isAnotherView = false;
    var valid = true;
    $('.data-table-column-check input:checked').not('.check-all').each(function() {
        var tr = $(this).closest('tr');
        var equipmentTypeObject = tr.attr('class').match(/equipment-\d+/);

        //check for group actions in periodic control index
        if(equipmentTypeObject == null) {
            isAnotherView = true;
            return true;
        }

        if (validateByEquipment && equipmentTypeObject[0] != compareEquipmentType && compareEquipmentType != '') {
            $('.different-type-alert').show();
            valid = false;
            return false;
        }

        compareEquipmentType = equipmentTypeObject[0];
    });

    if(isAnotherView)   return true;
    else {
        if (compareEquipmentType != '')
            compareEquipmentType = compareEquipmentType.replace('equipment-', '');

        return valid && compareEquipmentType > 0 ? compareEquipmentType : false;
    }
}

$(document).ready(function() {

    $('.different-type-alert .close').click(function(e) {
        $(this).parent().hide();
    });

    /**
     * Group Actions > Periodic control
     */
    $('a#periodic-control-action').click(function(e) {
        var selected = new Array();
        var compareEquipmentType = '';
        var controlIntervalDaysClass = '';
        //var areDifferent = true;
        $('.data-table-column-check input:checked').not('.check-all').each(function() {
            selected.push($(this).val());
            var tr = $(this).closest('tr');
            var equipmentTypeObject = tr.attr('class').match(/equipment-\d+/);

            var controlIntervalDaysObject = tr.attr('class').match(/intervalDays-\d+/);

            /*if (equipmentTypeObject[0] != compareEquipmentType && compareEquipmentType != '') {

                areDifferent = false;
                $('.different-type-alert').show();
                return false;
            }*/

            if(compareEquipmentType == '')
                compareEquipmentType = equipmentTypeObject[0];
            if(controlIntervalDaysClass == '')
                controlIntervalDaysClass = controlIntervalDaysObject[0];

        });

        // removing 'on ' values from array
        var removeItem = 'on';
        selected = jQuery.grep(selected, function(value) {
            return value !== removeItem;
        });
        var count = selected.length;
        if (count > 0) {
            //if (areDifferent) {
                // getting idd selected
                $('#confirm-periodic-control .modal-message').text(function(i, txt) {
                    return txt.replace(/\d+/, count);
                });
                var equipmentId = compareEquipmentType.split('-');
                var controlIntervalDays = controlIntervalDaysClass.split('-');

                // Show a confirm dialog before delete
                $('#confirm-periodic-control').modal('show');
                $("#confirm-periodic-control input[name='equipmentId']").val(equipmentId[1]);
                $("#confirm-periodic-control input[name='equipmentIntervalDays']").val(controlIntervalDays[1]);
            //}
        } else {
            $('.delete-alert').show();
        }
    });
    
    $('#url').wrap('<div class="input-prepend"></div>').before('<span class="add-on">&nbsp;http://</span>');

    $('a[data-rel="delete-selected"]').click(function(e) {
        var validEquipmentId = GetFirstEquipment(false);
        if(validEquipmentId) {
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
                // Showing the number 
                $('#count-locations').text( function(i,txt) {
                    return txt.replace(/\d+/, count); 
                });

                // put selected values in a hidden input
                $('#delete-list').val(selected);

                if($('#confirm-delete-many input[name=equipmentId]').val() == '')
                    $('#confirm-delete-many input[name=equipmentId]').val(validEquipmentId)

                // Show a confirm dialog before delete
                $('#confirm-delete-many').modal('show');
            } else {
                $('.delete-alert').show();
            }
        }
    });

    /**
     * this function show a form to edit many equipment instances
     */
    $('#edit-many').on('click', function(e) {
        e.preventDefault();
        var validEquipmentId = GetFirstEquipment(false);
        if(validEquipmentId) {
            var href = $(this).attr("href");
            if(validEquipmentId > 0)
                href = href.replace('equipmentId', validEquipmentId);
            
            $('form.data-table-form').attr('action', href);

            if ($(".data-table-form input:checkbox:checked").length > 0) {

                $('form.data-table-form').submit();
            } else {

                $('.delete-alert').show();
            }
        }
    });
    
    /**
     * Group Actions > Visual control
     */
    $('a#visual-control-action').click(function(e) {
        e.preventDefault();
        var validEquipmentId = GetFirstEquipment(false);
        if(validEquipmentId) {
            var checkedInstances = $('.data-table-column-check input:checked').not('.check-all');
            var count = checkedInstances.length;
            if (count > 0) {
                if($('#confirm-visual-control-modal input[name=equipmentId]').val() == '')
                    $('#confirm-visual-control-modal input[name=equipmentId]').val(validEquipmentId)

                $('#confirm-visual-control-modal .modal-message').text(function(i, txt) {
                    return txt.replace(/\d+/, count);
                });
                $('#confirm-visual-control-modal').modal('show');
            } else {
                $('.delete-alert').show();
            }
        }
    });

});