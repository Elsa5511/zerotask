if (!String.prototype.endsWith) {
    String.prototype.endsWith = function(searchString, position) {
        var subjectString = this.toString();
        if (typeof position !== 'number' || !isFinite(position) || Math.floor(position) !== position || position > subjectString.length) {
            position = subjectString.length;
        }
        position -= searchString.length;
        var lastIndex = subjectString.indexOf(searchString, position);
        return lastIndex !== -1 && lastIndex === position;
    };
}

$(document).ready(function() {

    if ($.isFunction($.fn.datepicker)) {
        var inputAppendDiv = '<div class="input-append"></div>';
        var iconCalendar = '<span class="add-on"><i class="icon-calendar"></i></span>';
        var formatDate = 'yyyy-mm-dd';

        $('input[type="date"]').each(function() {
            $(this).wrap(inputAppendDiv);
            $(this).parent().append(iconCalendar);
            $(this).datepicker({
                format: formatDate
            });
        })


    }
    
    $('select').not(".ajax-chosen").chosen(
            {
                placeholder_text_multiple: system.translations['multipleSelect'],
                allow_single_deselect: true,
                display_selected_options: true,
                disable_search: 	false,
                disable_search_threshold: 5
            }
    ).change(function() {

        removeSymbolTreeOnselected($(this));
    });
    if (typeof imageDecorator === 'undefined' || imageDecorator === true) {

        var fu = $('<div />', {
            'class': 'fileupload fileupload-new',
            'data-provides': 'fileupload',
            'html': '<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="/images/placehold_it.png" /></div><div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div><div><span class="btn btn-file"><span class="fileupload-new">' + system.translations['Select image'] + '</span><span class="fileupload-exists">' + system.translations['Change'] + '</span><input type="file" /></span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">' + system.translations['Remove'] + '</a></div>'
        });


    } else {
        var fu = $('<div />', {
            'class': 'fileupload fileupload-new',
            'data-provides': 'fileupload',
            'html': '<div class="input-append">' +
                    '<div class="uneditable-input span3">' +
                    '<i class="icon-file fileupload-exists"></i>' +
                    '<span class="fileupload-preview"></span>' +
                    '</div>' +
                    '<span class="btn btn-file">' +
                    '<span class="fileupload-new">' + system.translations['Select file'] + '</span>' +
                    '<span class="fileupload-exists">' + system.translations['Change'] + '</span>' +
                    '<input type="file" /></span>' +
                    '<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">' + system.translations['Remove'] + '</a>' +
                    '</div>'
        });
    }

    $('input[type=file]').each(function(i, e) {
        var fuc = fu.clone();

        fuc.find('input[type=file]').attr({
            name: e.name
        });

        $(e).replaceWith(fuc);
    });

    $('.fileupload').fileupload();


    formatSelectChosen();
    $("select").not(".ajax-chosen").each(function() {
        removeSymbolTreeOnselected($(this));
    });

    tooltip();
});

/**
 * this function format select as a tree
 * @returns {undefined}
 */
function formatSelectChosen() {
    $("select").not(".ajax-chosen").find("option").each(function() {
        newLabel =
                $(this).text().replace(/&nbsp;/gi, "&nbsp;&nbsp;");
        $(this).html(newLabel);


    });
    $("select").not(".ajax-chosen").trigger("chosen:updated");
}
/**
 * this function remove tree format on selected option
 * @param {type} object
 * @returns {undefined}
 */
function removeSymbolTreeOnselected(object) {
    if ($(object).parent().find('.chosen-single').length > 0) {

        cleanContent = $(object).parent().find('.chosen-single span').html().replace(/&nbsp;/gi, "").replace('|__', '');
        $(object).parent().find('.chosen-single span').html(cleanContent);

    } else if ($(object).parent().find('.chosen-container-multi').length > 0) {

        $(object).parent().find('.search-choice span').each(function() {
            cleanContent = $(this).html().replace(/&nbsp;/gi, "").replace('|__', '');
            $(this).html(cleanContent);
            //console.log(cleanContent);

        });

    }
}

function clearForm(form) {
    // iterate over all of the inputs for the given form element
    $(':input', form).each(function() {
        var type = this.type;
        var tag = this.tagName.toLowerCase(); // normalize case
        // it's ok to reset the value attr of text inputs, 
        // password inputs, and textareas
        if (type == 'text' || type == 'password' || tag == 'textarea')
            this.value = "";
        // checkboxes and radios need to have their checked state cleared 
        // but should *not* have their 'value' changed
        else if (type == 'checkbox' || type == 'radio')
            this.checked = false;
        // select elements need to have their 'selectedIndex' property set to -1
        // (this works for both single and multiple select elements)
        else if (tag == 'select')
            this.selectedIndex = -1;
    });
};

this.tooltip = function(){
    /* CONFIG */
    xOffset = 20;
    yOffset = -15;
    // these 2 variable determine popup's distance from the cursor
    // you might want to adjust to get the right result
    /* END CONFIG */
    $("a.custom-tooltip").hover(function(e){
            this.t = this.title;
            this.title = "";
            $("body").append("<p id='custom-tooltip'>"+ this.t +"</p>");
            $("#custom-tooltip")
                .css("top",(e.pageY + xOffset) + "px")
                .css("left",(e.pageX + yOffset) + "px")
                .fadeIn("fast");
        },
        function(){
            this.title = this.t;
            $("#custom-tooltip").remove();
        });
    $("a.custom-tooltip").mousemove(function(e){
        $("#custom-tooltip")
            .css("top",(e.pageY + xOffset) + "px")
            .css("left",(e.pageX + yOffset) + "px");
    });
};