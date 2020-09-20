$(document).ready(function() {

    /**
     * 
     */
    $("#exam").on("change", "#base-exercise", function() {
        if(this.value > 0) {
            showObligatoryQuestions(this.value);            
        } else {
            $(".questions").remove();
        }
    });

    /**
     * 
     */
    $("#question").on("click", "#add-opt-btn", function(e) {
        e.preventDefault();
        showOptionFieldset();
    });
    
    $("#question").on("click", "#del-opt-btn", function(e) {
        e.preventDefault();
        $(this).parents("fieldset").first().remove();
    });

    /**
     * Load and show obligatory questions in exam form
     * 
     * @param {integer} exerciseId
     * @returns {void}
     */
    function showObligatoryQuestions(exerciseId) {

        var $fieldset = $("#exam fieldset");
        $(".questions").remove();

        var divForQuestions = '<div class="questions"></div>';
        $fieldset.append(divForQuestions);

        var url = system.basePath + "/" + system.application + "/exam/questions/id/" + exerciseId;        
        var imgPath = system.basePath + '/images/ajax-loader.gif';
        var loading = '<img src="' + imgPath + '" />';
        var contentDiv = $fieldset.find(".questions");
        $(contentDiv).fadeTo(600, 1.0, function() {
            $(contentDiv).html(loading)
                    .load(url, function(responseText, textStatus, XMLHttpRequest) {
                $(this).fadeTo(300, 1.0);
            });
        });
    }
    
    function showOptionFieldset() {
        var template = 
         '<fieldset>' +
            '<div class="control-group">' +
               '<label class="control-label required">' + system.translations.optionText + '</label>' +
               '<div class="controls"><input name="question[options][__index__][optionText]" required="required" value="" type="text"></div>' +
            '</div>' +
            '<div class="control-group">' +
                '<label class="control-label">' + system.translations.isCorrectAnswer + '</label>' +
                '<div class="controls">' +
                    '<input name="question[options][__index__][isCorrect]" value="0" type="hidden">' +
                    '<input name="question[options][__index__][isCorrect]" value="1" type="checkbox">' +
                '</div>' +
            '</div>' +
            '<div class="control-group">' +
                '<div class="controls">' +
                    '<input type="submit" name="question[options][0][delete]" id="del-opt-btn" class="btn btn-primary btn-default" value="' + system.translations.deleteOption + '">' +
                '</div>' +
            '</div>' +
        '</fieldset>';

         var currentCount = $('form#question > fieldset > fieldset > fieldset').length;
         template = template.replace(/__index__/g, currentCount);

         $('form#question > fieldset > fieldset').append(template);

         return false;
    }
    
    $('.alert-error .close').click(function(e) {
        $('.alert-error').hide();
    });
    
    $("#submit-question-btn").click(function(e) {
        $(".alert-error").hide();
        var checkedInstances = $('form#question fieldset > fieldset input:checked');
        var count = checkedInstances.length;
        if (count > 0) {
            var questionType = $('input.question-type:checked').val();            
            if("one" === questionType &&
                    (count > 1)) {
                $("#one-option-alert").show();
                return false;                
            }
            return true;
        }
        $("#option-alert").show();
        return false;
    });
});