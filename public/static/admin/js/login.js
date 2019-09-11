//登陆
function login(url) {
        $.ajax({
            url:url,
            type:"post",
            dataType:"json",
            data:$("form").serialize(),
            success:function (result) {
                if (result.status==1){
                    dialog.OK(result.data,result.jump_url);
                }else {
                    layer.msg(result.data,{icon:5,time:1500});
                    // setTimeout("location.href=''",1000);
                }
            }
        });
}
//刷新验证码
