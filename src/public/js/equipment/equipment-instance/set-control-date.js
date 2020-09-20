Date.prototype.addDays = function(days)
{
    var dat = new Date(this.valueOf());
    dat.setDate(dat.getDate() + days);
    return dat;
}

$(document).ready(function() {
    var dateElements = $('#reception-control, #production-date, #purchase-date, #first-time-used');
    dateElements.on('changeDate', function() {
        var datesArray = new Array();
        dateElements.each(function() {
            var dateValue = $(this).val();
            if(dateValue) {
                var dateParts = dateValue.split('-');
                console.log(dateParts);
                var dateObject = new Date(dateParts[0], dateParts[1]-1, dateParts[2]);
                console.log(dateObject);
                datesArray.push(dateObject);
            }
        });
        var maxDate=new Date(Math.max.apply(null,datesArray));
        var controlDate = new Date(maxDate.getUTCFullYear(), maxDate.getUTCMonth(), maxDate.getUTCDate());
        controlDate = controlDate.addDays(controlInterval);
        $('#periodic-control-date').datepicker('setValue', controlDate);
    });


    $('#periodic-control-date').parents(".controls").find(".help-block").append( $( '#control-interval-for-equipment' ) );
});


