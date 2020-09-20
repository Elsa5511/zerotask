

$(document).ready(function () {
    function showModalBox(url, title) {
        var windowHeight = window.innerHeight
            || document.documentElement.clientHeight
            || document.body.clientHeight || 700;

        $.modalbox({
            id: 'open-attachment',
            type: 'iframe',
            title: title,
            source: url,
            height: windowHeight * 0.8,
            cssClass: "container",
            callback: 'resizeImage'
        });
    }

    /*
     *this method open attachment in thumbnail views
     */
    $('a[data-rel=open-attachment]').on('click', function (e) {

        e.preventDefault();
        var url = $(this).attr("href");
        title = $(this).attr("title");
        if (typeof title === 'undefined' || title == null) {
            var title = $(this).find('h2').text();
        }
        var extensionFile = $(this).attr("data-extension");

        var result = checkFormatFile(extensionFile);

        displayResult($(this), result, url, title);

    });

    /**
     * this method is for open attachment in table list
     */
    $('tr a[data-rel=open]').on('click', function (e) {

        e.preventDefault();
        var url = $(this).attr("href");
        var extensionFile = $(this).parent().siblings(".data-table-column-file").text();
        var result = checkFormatFile(extensionFile);
        displayResult($(this), result, url);


    });

    function displayResult(element, result, url, title) {


        if (typeof (title) === 'undefined') {
            title = element.parent().siblings(".data-table-column-title").text();
        }
        if (result['pdf']) {
            window.open(url, '_blank');
        } else if (result['image']) {

            showModalBox(url, title);

        } else if (result['video'] && checkVideoCanPlay() && (result['extension'] === 'mp4' || result['extension'] === 'mov')) {

            url = url.replace('handle', 'video-handle');
            showModalBox(url, title);

        } else if(result['extension'] == 'url') {
            window.open($(element).attr('data-link'));
        } else {
            location.href = url;
        }
    }

    function resizeImage() {
        $("#open-attachment-iframe").contents().find("img")
            .css(
            {
                'max-height': '390px',
                'width': 'auto',
                'height': 'auto',
                'display': 'block',
                'margin': 'auto'
            });
    }

    function checkVideoCanPlay() {
        var canPlay = false;
        var videoElement = document.createElement('video');
        if (videoElement.canPlayType && videoElement.canPlayType('video/mp4;codecs="avc1.42E01E, mp4a.40.2"').replace(/no/, '')) {
            canPlay = true;
        }
        return canPlay;
    }

    function checkFormatFile(extensionFile) {
        var isPDF = (extensionFile.indexOf("pdf") === 0);
        var isJPG = (extensionFile.indexOf("jp") === 0);
        var isGIF = (extensionFile.indexOf("gif") === 0);
        var isPNG = (extensionFile.indexOf("png") === 0);
        var isImage = isJPG || isGIF || isPNG;

        var isWMV = (extensionFile.indexOf("wmv") === 0);
        var isMP4 = (extensionFile.indexOf("mp4") === 0);
        var isMOV = (extensionFile.indexOf("mov") === 0);
        var isAVI = (extensionFile.indexOf("avi") === 0);

        var isVideo = isWMV || isMP4 || isMOV || isAVI;
        var result = new Array();
        result['video'] = isVideo;
        result['image'] = isImage;
        result['pdf'] = isPDF;
        result['extension'] = extensionFile;
        return result;

    }
});