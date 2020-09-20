$('a[data-rel=open-attachment]').on('click', function(e) {
    e.preventDefault();
    var url = $(this).attr("href");
    var title = $(this).find('h2').text();


    showModalBox(url);
});
function showModalBox(url, title) {


    $.modalbox({
        id: 'open-attachment',
        type: 'iframe',
        title: 'pending',
        source: url,
        height: "400",
        callback: 'resizeImage'
    });

}