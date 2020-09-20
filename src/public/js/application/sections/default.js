/*
 * This file is for open iframe
 */
$(document).ready(function() {
   
    

    $('.add-edit-section').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr("href");

        $.modalbox({
            type: 'iframe',
            id: "iframeId",
            title: titleIframe,
            source: href,
            height: "255",
            buttons: ['cancel', {
                    'text': saveButtonName,
                    'class': 'btn btn-primary',
                    'on': {
                        'click': function() {
                            document.getElementById("iframeId-iframe").contentWindow.document.getElementById("section-form").submit();

                        }
                    }
                }]
        });
    });

});

