$(document).ready(function() {

    function GetFirstEquipmentId() {
        var firstEquipment = '';
        var isAnotherView = false;
        $('.data-table-column-check input:checked').not('.check-all').each(function() {
            var tr = $(this).closest('tr');
            var equipmentTypeObject = tr.attr('class').match(/equipment-\d+/);

            if(equipmentTypeObject == null){
                isAnotherView = true;
                return true;
            }

            if(firstEquipment == '')
                firstEquipment = equipmentTypeObject[0];
        });

        if(isAnotherView)   return true;
        else {
            if (firstEquipment != '')
                firstEquipment = firstEquipment.replace('equipment-', '');

            return firstEquipment > 0 ? firstEquipment : false;
        }
    }

    /**
     * Group Actions > Check-out
     */
    $('#check-out-button').click(function(e) {
        var validEquipmentId = GetFirstEquipmentId();

        if(validEquipmentId) {
            var checkedInstances = $('.data-table-column-check input:checked').not('.check-all');

            var count = checkedInstances.length;
            if (count > 0) {
                if($('#confirm-checkout-modal input[name=equipmentId]').val() == '')
                    $('#confirm-checkout-modal input[name=equipmentId]').val(validEquipmentId)
                
                $('#confirm-checkout-modal .modal-message').text(function(i, txt) {
                    return txt.replace(/\d+/, count);
                });
                $('#confirm-checkout-modal').modal('show');

            } else {
                $('.delete-alert').show();
            }
        }
    });

    $("#detail-checkout").on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
         $.modalbox({
            type: 'iframe',
            id: "iframeId",
            title: titleIframeCheckout,
            source: href,
            height: "300",
            width:768
        }); 
    });
    
    $("#add-subinstance").on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
         $.modalbox({
            type: 'iframe',
            id: "iframeId",
            title: titleIframeSubinstance,
            source: href,
            height: 240,
            width: 600
        }); 
    });
});