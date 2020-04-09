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
});
function layer_open(title, url, w, h) {
    if (title == null || title === '') {
        title = false;
    }
    if (url == null || url === '') {
        url = "404.html";
    }
    if (w == null || w === '') {
        w = 800;
    }
    if (h == null || h === '') {
        h = ($(window).height() - 50);
    }
    layer.open({
        type: 2,
        area: [w + 'px', h + 'px'],
        fix: false, //不固定
        maxmin: true,
        shade: 0.4,
        title: title,
        content: url
    });
}