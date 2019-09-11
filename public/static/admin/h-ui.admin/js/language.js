/***
 *
 */
// $('.table-sort').dataTable({
//     // //"aaSorting": [[ 1, "desc" ]],//默认第几个排序
//     // "bStateSave": true,//状态保存
//     // "aoColumnDefs": [
//     //     //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
//     //     {"orderable":false,"aTargets":[0,6]}// 不参与排序的列
//     // ]
// });

/*资讯-添加*/
function article_add(title, url, w, h) {
    // var index = layer.open({
    //     type: 2,
    //     title: title,
    //     content: url
    // });
    // layer.full(index);
    layer_show(title, url, w, h);
}
/*资讯-编辑*/
function article_edit(title, url, id, w, h) {
    // var index = layer.open({
    //     type: 2,
    //     title: title,
    //     content: url,
    // });
    layer_show(title, url, h, w);
}
/*资讯-删除*/
// function article_del(obj, url) {
//     layer.confirm('确认要删除吗？', function (index) {
//         $.ajax({
//             type: 'POST',
//             url: url,
//             dataType: 'json',
//             success: function (data) {
//                 $(obj).parents().remove();
//                 layer.msg('已删除！', {icon: 1, time: 100});
//             }
//         });
//     })
// };
function language_del(url) {
    layer.confirm('确认要删除吗？',function () {
        // console.log(url);
        $.ajax({
            type:"post",
            url:url,
            dataType:'json',
            success:function (result) {
                if (result.status == 1){
                    layer.msg(result.data,{icon: 5,time:2000});
                    setTimeout("location.href='system_language'",2000)
                }else {
                    layer.msg(result.data,{icon: 5, time: 2000})
                }
                //window.parent.location.replace(location.href)
            }
        });
    })
}
/*批量删除*/
function delall(url) {
    layer.confirm("确定要删除吗?",function () {
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
                    layer.msg(result.data,{icon: 5,time:2000});
                    //延迟2秒刷新当前页面
                    setTimeout("location.href='system_language'",2000)
                }else{
                    layer.msg(result.data,{icon: 5,time: 2000})
                }

            }
        })
    })
}

/*资讯-审核*/
function article_shenhe(obj, id) {
    layer.confirm('审核文章？', {
            btn: ['通过', '不通过', '取消'],
            shade: false,
            closeBtn: 0
        },
        function () {
            $(obj).parents("tr").find(".td-manage").prepend('<a class="c-primary" onClick="article_start(this,id)" href="javascript:;" title="申请上线">申请上线</a>');
            $(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">已发布</span>');
            $(obj).remove();
            layer.msg('已发布', {icon: 6, time: 1000});
        },
        function () {
            $(obj).parents("tr").find(".td-manage").prepend('<a class="c-primary" onClick="article_shenqing(this,id)" href="javascript:;" title="申请上线">申请上线</a>');
            $(obj).parents("tr").find(".td-status").html('<span class="label label-danger radius">未通过</span>');
            $(obj).remove();
            layer.msg('未通过', {icon: 5, time: 1000});
        });
}
/*资讯-下架*/
function article_stop(obj, id) {
    layer.confirm('确认要下架吗？', function (index) {
        $(obj).parents("tr").find(".td-manage").prepend('<a style="text-decoration:none" onClick="article_start(this,id)" href="javascript:;" title="发布"><i class="Hui-iconfont">&#xe603;</i></a>');
        $(obj).parents("tr").find(".td-status").html('<span class="label label-defaunt radius">已下架</span>');
        $(obj).remove();
        layer.msg('已下架!', {icon: 5, time: 1000});
    });
}

/*资讯-发布*/
function article_start(obj, id) {
    layer.confirm('确认要发布吗？', function (index) {
        $(obj).parents("tr").find(".td-manage").prepend('<a style="text-decoration:none" onClick="article_stop(this,id)" href="javascript:;" title="下架"><i class="Hui-iconfont">&#xe6de;</i></a>');
        $(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">已发布</span>');
        $(obj).remove();
        layer.msg('已发布!', {icon: 6, time: 1000});
    });
}
/*资讯-申请上线*/
function article_shenqing(obj, id) {
    $(obj).parents("tr").find(".td-status").html('<span class="label label-default radius">待审核</span>');
    $(obj).parents("tr").find(".td-manage").html("");
    layer.msg('已提交申请，耐心等待审核!', {icon: 1, time: 2000});
}