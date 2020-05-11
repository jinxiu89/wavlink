function loginRegisterBox () {
    var formBox = $('#box');
    var formBoxH = formBox.outerHeight(true);
    var windowH = $(window).outerHeight(true);
    var marginTop = (windowH - formBoxH) / 2;
    if (windowH > formBoxH) {
        formBox.css({
            'marginTop': marginTop
        })
    } else {
        formBox.css({
            'margin': '5% auto'
        })
    }
}

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
        skin: 'wavlink-layer',
        type: 2,
        area: [w + 'px', h + 'px'],
        fix: false, //不固定
        maxmin: true,
        shade: 0.4,
        title: title,
        content: url,
        shadeClose: true
    });
}

function layer_close() {
    $('#btn-close').click(function (){
        var index = parent.layer.getFrameIndex(window.name);
        parent.layer.close(index);
    })
}

function Account() {
    var Dom = $('#account');
    Dom.hover(function () {
        $(this).find('#user-menu-wrapper').stop(false, true).slideToggle(50)
    })
}

function PersonImg() {
    var uploadBtn = $('.UploadPicture-btn'),
        uploadInput = $('#UploadPicture-input'),
        PersonBox = $('#person-box');
    PersonBox.hover(function () {
        $(this).find('#img-edit').stop(false, true).fadeToggle(300)
    });
    uploadBtn.on('click', function () {
        uploadInput.click() //点击按钮触发input
    })
}

function InfoChange(url, form) {
    $.ajax({
        url: url,
        type: "post",
        dataType: "json",
        data: $(form).serialize(),//提交表单数据
        success: function (result) {
            if (result.status === 1) {
                //更新信息成功后刷新页面
                layer.msg(result.message, {icon: 1, time: 1000}, function () {
                    window.parent.location.href = result.jump_url
                });
            } else {
                layer.msg(result.message, {icon: 5, time: 3000});
            }
        }
    })
}

function handleFootHover () {
    var footItem = $('.g-footer .foot-container dl dd a');
    footItem.hover(function () {
        if ($(this).siblings('img').length > 0) {
            $(this).parent('dd').siblings('dd').children('img').css({'display': 'none'});
            $(this).siblings('img').css({'display': 'block'})
        }
    })
}

$(document).ready(function () {
    loginRegisterBox();
    Account();
    PersonImg();
    handleFootHover();
});