
$(document).ready(function() {

	$.fn.dataTableExt.sErrMode = 'throw';
	/* Datatables */
    $('.data-table-net').each(function() {
        if ( $(this).find('tbody td').length > 1 ) {
            $(this).dataTable( {
                "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
                "sPaginationType": "bootstrap",
                "bAutoWidth": false,
                "oLanguage": {
                    "sUrl": system.basePath + "/includes/datatables-lan/" + system.language + ".txt"
                },
                "aoColumnDefs": [ { 'bSortable': false, 'aTargets': ['column-options',  'data-table-column-check' ] } ],
                "fnInitComplete": function(oSettings, json) {
                    $(this).trigger("datatable-loaded");
                },
                "fnDrawCallback": function(oSettings, json) {
                    $(this).trigger("datatable-draw");
                }
            });

        } else {
            $(this).find('tbody').show();
            $(this).addClass('loaded');
        }
    });



    $("body").on("datatable-loaded", function () {
        $('.data-table-net').find('tbody').show();
        $('.data-table-net').addClass('loaded');
    });


    $.extend( $.fn.dataTableExt.oStdClasses, {
        "sWrapper": "dataTables_wrapper form-inline"
    });
    
    /* Edit buttons */
    $('.edit-buttons-toggle').mouseover(function(){
        $(this).find('.edit-buttons').first().show();
    });
    
    $('.edit-buttons-toggle').mouseout(function(){
        $(this).find('.edit-buttons').first().hide();
    });
    
    $('.edit-buttons').mouseover(function(){
        $(this).show();
    });
    
    /* Toogle scrollspy */
    if($('.scrollspy-nav').length > 0) {
        var $window_width = $(window).width();
        if($window_width > 979) {
            $('body').scrollspy();
            $('body').data().scrollspy.options.offset = 60;
            $('body').data().scrollspy.process();
            setTimeout(function () {
                $('.scrollspy-nav').affix({
                    offset: {
                      top: function () { return $window_width < 980 ? 210 : 155 }
                    , bottom: 270
                    }
                })
            }, 100)
        }
        
        $('.scrollspy-nav a').click(function (e) {
            e.preventDefault();
            var offset = $('section[id=' + $(this).attr('href').substr(1) + ']').offset();
            if (typeof(offset) !== 'undefined') {
                $('html, body').animate({ scrollTop: offset.top - 50 }, 'fast');
            }
        });        
    }
    
    /* Fix problem with validation messages on inputs with .input-append */
    $('.input-append .help-inline').each(function(){
        $(this).parent().find('.add-on').insertAfter($(this).parent().find('input'));
    })
    
    /* Open links with target="bootstrap-modal" in modal window */
    $('a[target="bootstrap-modal"]').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr("href");
        var titleIframe = $(this).attr('data-title') || $(this).text();

        if (navigator.appName == 'Microsoft Internet Explorer' ||  !!(navigator.userAgent.match(/Trident/) || navigator.userAgent.match(/rv 11/))) {
            if(href.endsWith(".pdf")) {
                window.open(href, '_blank');
                return false;
            }
        }

        var options = {
            type: 'iframe',
            id: "bootstrap-modal",
            cssClass: "container",
            title: titleIframe,
            source: href,
            height: "600px"
        };

        if($(this).attr("data-height"))
            options.height = $(this).attr("data-height");
        if($(this).attr("data-width"))
            options.width = $(this).attr("data-width");

        $.modalbox(options);

        $(function() {
            var iframeModal = $('#bootstrap-modal-iframe');
            iframeModal.load(function() {
                iframeModal.contents().find('.vidum .navbar-fixed-top').hide();
                iframeModal.contents().find('.vidum footer.footer').hide();
                iframeModal.contents().find('.vidum form.form-search').hide();
            });
        });
    });

    $("select.ajax-chosen").attr('search', '');
    $("select.ajax-chosen").each(function() {
        var controller = $(this).attr('data-controller');
        var action = $(this).attr('data-action');
        var url = '/' + system.application + '/' + controller + '/' + action;

        $(this).ajaxChosen({
                type: 'GET',
                url: url,
                dataType: 'json'
            }
        );
    });
    
    $('.LADOC-documentation table[border="0"]').addClass('table').addClass('table-bordered');
});

function resizeImage() {
    $("#open-attachment-iframe").contents().find("img")
        .css({
            'max-width': '100%',
            'width': 'auto',
            'height': 'auto',
            'display': 'block',
            'margin': 'auto'
        });
}
