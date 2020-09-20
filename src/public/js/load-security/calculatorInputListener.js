$(document).ready(function() {

    if(typeof String.prototype.trim !== 'function') {
        String.prototype.trim = function() {
            return this.replace(/^\s+|\s+$/g, '');
        }
    }

    function rotate(name, imgUrl, degrees) {
        position = {
            "top": $("#" + name).css("top"),
            "left": $("#" + name).css("left")
        };

        rotateArrow(name, imgUrl, degrees, position);

    }

    function convertToValidAngle(angleString) {
        var angle = Number(angleString);
        if (isNaN(angle) || angle < 0) {
            return 0;
        }
        else if (angle > 90) {
            return 90;
        }
        else {
            return angle;
        }
    }

    function allInputsAreValid() {
        return validate($("#loadCapacity"))
                & validate($("#noOfTyings"))
                & validate($("#horizontalAngleInput"))
                & validate($("#verticalAngleInput"));
    }

    function validate(inputElement) {
        inputElement.popover('destroy');
        var inputValue = inputElement.val();
        var isValid = false;
        var errorMessage = '';

        if (inputValue.trim() === '') {
            errorMessage = $("#errorMessages").data("empty-field");
        }
        else if (isNaN(Number(inputValue))) {
            errorMessage = $("#errorMessages").data("invalid-value");
        }
        else {
            isValid = true;
        }
        
        if (!isValid) {
            var controlGroup = inputElement.closest('div[class^="control-group"]');
            controlGroup.addClass("error");
            inputElement.popover({content: errorMessage});
            inputElement.popover('show');
        }
        else {
            var controlGroup = inputElement.closest('div[class^="control-group"]');
            controlGroup.removeClass("error");
        }
        
        return isValid;
    }


    $("#calculateLoadSecurity").click(function() {
        if (allInputsAreValid()) {
            calculatorInput = {
                "horizontalAngle": $("#horizontalAngleInput").val(),
                "verticalAngle": $("#verticalAngleInput").val(),
                "noOfTyings": $("#noOfTyings").val(),
                "loadCapacity": $("#loadCapacity").val()
            };
            var calculatorResult = getCalculatorResult(calculatorInput);
            $("#loadSecurityResult").val(number_format(calculatorResult, 0, ',', ' '));

        }
    });

    $("#horizontalAngleInput").keyup(function() {
        var validAngle = convertToValidAngle($("#horizontalAngleInput").val());
        rotate("horizontalAngleLine", system.basePath + "/images/load-security/line-left.png", validAngle);
    });

    $("#horizontalAngleInput").change(function() {
        var validAngle = convertToValidAngle($("#horizontalAngleInput").val());
        $("#horizontalAngleInput").val(validAngle);
    });

    $("#verticalAngleInput").keyup(function() {
        var validAngle = convertToValidAngle($("#verticalAngleInput").val());
        rotate("upperVerticalAngleLine", system.basePath + "/images/load-security/line-down.png", -validAngle);
        rotate("lowerVerticalAngleLine", system.basePath + "/images/load-security/line-down.png", validAngle);
    });

    $("#verticalAngleInput").change(function() {
        var validAngle = convertToValidAngle($("#verticalAngleInput").val());
        $("#verticalAngleInput").val(validAngle);
    });


});