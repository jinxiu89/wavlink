$(function () {
    var formBox = $('#box');
    var formBoxH = formBox.outerHeight(true);
    var windowH = $(window).outerHeight(true);
    var marginTop = (windowH - formBoxH)/2;
    if (windowH > formBoxH) {
        formBox.css({
            'marginTop': marginTop
        })
    } else {
        formBox.css({
            'margin': '5% auto'
        })
    }
})