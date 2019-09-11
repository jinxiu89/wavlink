/*
	参数解释：
	title	标题
	url		请求的url
	id		需要操作的数据id
	w		弹出层宽度（缺省调默认值）
	h		弹出层高度（缺省调默认值）
*/
/*管理员-增加*/
function admin_add(title,url){
    layer_show(title,url);
}

/*管理员-删除*/
function admin_del(url){
    layer.confirm('确定要删除吗？',function () {
        $.ajax({
            type:"post",
            url:url,
            dataType:'json',
            success:function (result) {
                if(result.status == 1){
                    // $(obj).parent("tr").remove();
                    layer.msg(result.data,{icon:1,time:2000});
                    window.location.reload(true);//成功后跳转到当前页面，
                    //setTimeout("location.href='manger_list'",500)
                }else{
                    layer.msg(result.data,{icon:2,time:2000})
                }
            }
        })
    })
}
/*管理员—批量删除*/
function admin_delall(url) {
    layer.confirm("确定要全部删除吗?",function () {
        var ids = $("input[name='id']:checked").serializeArray();/***
         $()是一个选择器：意思是说 将input标签里name=ID且是选中状态的项选择下来，serializeArray（）是将选中的项序列化获取数据
         */
        var postData = {};
        $(ids).each(function () {
            postData[this.value] = this.name
        });
        $.ajax({
            url:url,
            type:"post",
            dataType:"json",
            data:postData,
            success:function (result) {
                if(result.status == 1){
                    layer.msg(result.data,{icon: 1,time:2000});
                    //延迟2秒刷新当前页面
                    setTimeout("location.href='manger_list'",2000)
                }else{
                    layer.msg(result.data,{icon: 5,time: 2000})
                }

            }
        })
    })
}
/*管理员-编辑*/
function admin_edit(title,url,w,h){
    layer_show(title,url,w,h);
}
/*管理员-停用*/
function admin_stop(obj,id){
    layer.confirm('确认要停用吗？',function(index){
        //此处请求后台程序，下方是成功后的前台处理……
        $(obj).parents("tr").find(".td-manage").prepend('<a onClick="admin_start(this,id)" href="javascript:;" title="启用" style="text-decoration:none"><i class="Hui-iconfont">&#xe615;</i></a>');
        $(obj).parents("tr").find(".td-status").html('<span class="label label-default radius">已禁用</span>');
        $(obj).remove();
        layer.msg('已停用!',{icon: 5,time:1000});
    });
}
/*管理员-启用*/
function admin_start(obj,id){
    layer.confirm('确认要启用吗？',function(index){
        //此处请求后台程序，下方是成功后的前台处理……

        $(obj).parents("tr").find(".td-manage").prepend('<a onClick="admin_stop(this,id)" href="javascript:;" title="停用" style="text-decoration:none"><i class="Hui-iconfont">&#xe631;</i></a>');
        $(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">已启用</span>');
        $(obj).remove();
        layer.msg('已启用!', {icon: 6,time:1000});
    });
}
/**
 * 在排序失去焦点后执行这个
 */
$('.listorder input').blur(function () {
    //获取主键id,失去焦点获取主键id
    var id = $(this).attr('attr-id');
    //获取排序的值
    var listorder = $(this).val();
    //把id 和listorder 组装成一个数组
    var postData = {
        'id':id,
        'listorder':listorder,
    };
    var url = SCOPE.listorder_url;
    $.post(url,postData,function (result) {
        if(result.code == 1){
            location.href= result.data;
        }else{
            alert(result.msg);
        }
    },"json");
 });
