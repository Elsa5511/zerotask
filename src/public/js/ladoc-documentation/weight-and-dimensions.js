$(document).ready(function() {
    $("#ladoc-previous-button").click(function() {
        $("#direction-field").val("previous");
        $("#weight-and-dimensions").submit();
    });

    $("#ladoc-continue-button").click(function(event) {
        event.preventDefault();
        $("#direction-field").val("next");
        $("#weight-and-dimensions").submit();
    });
});
