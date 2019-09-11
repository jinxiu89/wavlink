var dialog = {
    //错误弹出
    error:function (message, title, btn) {
        layer.open({
            content:message,
            icon:2,
            title:title,
            btn:[btn]
        });
    },
    //成功弹出层
    success:function (message, url,title,btn) {
        layer.open({
            content:message,
            icon:1,
            title:title,
            area: '500px',
            yes:function () {
                location.href=url;
            },
            btn:[btn]
        });
    },
    OK:function (message,url) {
      layer.msg(message,{icon:1,time:1500});
      setTimeout("window.location.href = '" + url + "'",1500);
    },
    None:function (message,url) {
        layer.msg(message,{icon:5,time:1500});
        setTimeout("window.location.href = '" + url + "'",1500);
    },
    //确认弹出层
    confirm:function (message,url) {
        layer.open({
            content:message,
            icon:3,
            bth:['是','否'],
            yes:function () {
                location.href=url;
            }
        });
    },
    //无需跳转到指定页面的确认弹出层
    toconfirm:function (message) {
        layer.open({
            content:message,
            icon:3,
            btn:['确定']
        });
    }
};