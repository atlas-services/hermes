(function ($) {
    "use strict"; // Start of use strict

    //test
    var btncopy = 'btncopy-jazzevenement';
    var jscopybase = '.js-copy-jazzevenement';
    var jscopyclass = '';
    for (var i = 1; i <= 15; i++) {
        jscopyclass = jscopybase + i;
        window[btncopy + i] = document.querySelector(jscopyclass);
        if (window[btncopy + i]) {
            window[btncopy + i].addEventListener('click', docopy);
        }
    }
    //test atlas
    var btncopy = 'btncopy-atlas';
    var jscopybase = '.js-copy-atlas';
    var jscopyclass = '';
    for (var i = 1; i <= 5; i++) {
        jscopyclass = jscopybase + i;
        window[btncopy + i] = document.querySelector(jscopyclass);
        if (window[btncopy + i]) {
            window[btncopy + i].addEventListener('click', docopy);
        }
    }

    //test gallerie
    var btncopy = 'btncopy-gallerie';
    var jscopybase = '.js-copy-gallerie';
    var jscopyclass = '';
    for (var i = 1; i <= 5; i++) {
        jscopyclass = jscopybase + i;
        window[btncopy + i] = document.querySelector(jscopyclass);
        if (window[btncopy + i]) {
            window[btncopy + i].addEventListener('click', docopy);
        }
    }
    //test image
    var btncopy = 'btncopy-image';
    var jscopybase = '.js-copy-image';
    var jscopyclass = '';
    for (var i = 1; i <= 5; i++) {
        jscopyclass = jscopybase + i;
        window[btncopy + i] = document.querySelector(jscopyclass);
        if (window[btncopy + i]) {
            window[btncopy + i].addEventListener('click', docopy);
        }
    }


    //texte
    var btncopy = 'btncopy-texte';
    var jscopybase = '.js-copy-texte';
    var jscopyclass = '';
    for (var i = 1; i <= 5; i++) {
        jscopyclass = jscopybase + i;
        window[btncopy + i] = document.querySelector(jscopyclass);
        if (window[btncopy + i]) {
            window[btncopy + i].addEventListener('click', docopy);
        }
    }

    //texte cuisine
    var btncopy = 'btncopy-texte-cuisine';
    var jscopybase = '.js-copy-texte-cuisine';
    var jscopyclass = '';
    for (var i = 1; i <= 5; i++) {
        jscopyclass = jscopybase + i;
        window[btncopy + i] = document.querySelector(jscopyclass);
        if (window[btncopy + i]) {
            window[btncopy + i].addEventListener('click', docopy);
        }
    }
    //videos
    var btncopy = 'btncopy-video';
    var jscopybase = '.js-copy-video';
    var jscopyclass = '';
    for (var i = 1; i <= 5; i++) {
        jscopyclass = jscopybase + i;
        window[btncopy + i] = document.querySelector(jscopyclass);
        if (window[btncopy + i]) {
            window[btncopy + i].addEventListener('click', docopy);
        }
    }

    function docopy(e) {

        e.preventDefault(); // Cancel the native event
        e.stopPropagation();// Don't bubble/capture the event

        var range = document.createRange();
        var target = this.dataset.target;
        var fromElement = document.querySelector(target);
        var selection = window.getSelection();
        var type = fromElement.getAttribute('data-copy-type');

        // range.selectNode(fromElement);
        // selection.removeAllRanges();
        // selection.addRange(range);

        try {
            // document.execCommand("Copy");
            if (type != 'html') {
                range.selectNode(fromElement);
                selection.removeAllRanges();
                selection.addRange(range);
                var result = document.execCommand('copy');
            } else {
                const copy = require('clipboard-copy');
                var copyHTML = document.getElementById(fromElement.getAttribute('id')).innerHTML;
                if (typeof copyHTML === 'string') {
                    copy(copyHTML); // inenrHtml
                    $("#cke_17_label").click();
                    $("textarea.cke_source").html(copyHTML);

                    result = true;
                }
                if (result) {
                    // La copie a réussi
                    $(".close-modal").click();
                    $("#copyinfohtml").click();
                }
            }
        } catch (err) {
            // Une erreur est surevnue lors de la tentative de copie
            alert(err);
        }

        if (type != 'html') {
            selection = window.getSelection();
            if (typeof selection.removeRange === 'function') {
                selection.removeRange(range);
            } else if (typeof selection.removeAllRanges === 'function') {
                selection.removeAllRanges();
            }
            $(".close-modal").click();
            $("#copyinfo").click();

        }
    }
})(jQuery); // End of use strict
