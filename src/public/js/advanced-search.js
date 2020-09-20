$(document).ready(function() {
    $('.advanced-search-toggle').on('click', function() {
        var dataTarget = $(this).attr('data-target');
        var divToShow = $(dataTarget);
        if(!divToShow.hasClass('in')) {
            //This validation collapse the other divs only when you try to show a div (not enters when you try to hide)
            //the "in" class identifies when the div is showed, but in this case this event fires before this class is put
            var divsToHide = $('.advanced-search-collapsible').not(dataTarget);
            $(divsToHide).each(function() {
                if($(this).hasClass('in'))
                    $(this).collapse('hide');
            });

        }
    });
});