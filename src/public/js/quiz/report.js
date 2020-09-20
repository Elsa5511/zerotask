jQuery(document).ready(function(){
    $(".controls button#show-all").on("click", function(e){
        e.preventDefault();
        var form = $(this).parents('form#quiz-search')[0];
        clearForm(form);
        $(form).submit();
    });
});