/**
 * Created by guo on 2018/1/4.
 * modify by JinXiu89 on 201811/13
 */
function ticket_reply(title,url,w,h) {
    layer_show(title,url ,w, h);
}
function send_email(url) {
    $.ajax({
        url: url,
        type: "post",
        dataType: "json",
        data: $("form").serialize(),//提交表单数据
        success: function (result) {
            if (result.status === 1) {
                layer.msg(result.data, {icon: 1, time:2000});
                setTimeout(function () {
                    window.parent.parent.location.reload();
                },3000);
            } else {
                layer.msg(result.data, {icon: 5, time: 1500});
            }
        }
    })
}