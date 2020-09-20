$(document).ready(function() {
    var _totalTimeInSeconds = $("#examTimer").data("total-time-in-seconds");
    var _totalTimeLeftInSeconds = $("#examTimer").data("time-left-in-seconds");        
    var _startTimeInSeconds = new Date() / 1000;
    
    function zeroFill(number, width)
    {
        width -= number.toString().length;
        if (width > 0)
        {
            return new Array(width + (/\./.test(number) ? 2 : 1)).join('0') + number;
        }
        return number + "";
    }

    function formatRemainingTime(remainingSeconds) {
        var remainingTime = "00:00:00";
        if (remainingSeconds > 0) {
            var hoursRemaining = zeroFill(Math.floor(remainingSeconds / 60 / 60), 2);
            var minutesRemaining = zeroFill((Math.floor(remainingSeconds / 60) % 60), 2);
            var secondsRemaining = zeroFill((Math.floor(remainingSeconds) % 60), 2);
            remainingTime = hoursRemaining + ":" + minutesRemaining + ":" + secondsRemaining;
        }
        return remainingTime;
    }
    
    function addColorCode(totalTime, remainingTime) {
        if (remainingTime / totalTime <= 0.1) {
            $("#examTimer").addClass("yellow");
        }
        if (remainingTime / totalTime <= 0.02) {
            $("#examTimer").addClass("red");
        }        
    }

    (function() { // Update timer
        var nowInSeconds = new Date() / 1000;
        var timePassed = Math.floor(nowInSeconds - _startTimeInSeconds);
        var remainingTimeInSeconds = _totalTimeLeftInSeconds - timePassed;
        var remainingTimeString = formatRemainingTime(remainingTimeInSeconds);
        addColorCode(_totalTimeInSeconds, remainingTimeInSeconds);
        $("#examTimer").html(remainingTimeString);
        if (remainingTimeInSeconds >= 0) {
            setTimeout(arguments.callee, 1000); // Call itself every second
        }        
    })();
});
