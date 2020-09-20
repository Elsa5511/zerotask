$(document).ready(function () {
    var periodDropDown = $("#period_type");
    disableControlSpesificFields();

    periodDropDown.change(function () {
        var selectedResult = $("#period_type").val();
        var futureResult = "future";
        if (selectedResult === futureResult) {
            disableControlSpesificFields();
        }
        else {
            enableControlSpesificFields();
        }
    });

    function disableControlSpesificFields() {
        $(".control-spesific").attr("disabled", true).trigger("chosen:updated");
    }

    function enableControlSpesificFields() {
        $(".control-spesific").attr("disabled", false).trigger("chosen:updated");
    }
});

