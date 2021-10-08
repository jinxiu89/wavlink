// /**分类相关二级内容**/
// $(".categoryId").change(function(){
//     category_id = $(this).val();
//     // 抛送请求
//     url = SCOPE.category_url;
//     postData = {'id':category_id};
//     $.post(url,postData,function(result){
//         //相关的业务处理
//         if(result.status == 1) {
//             data = result.data;
//             category_html = "";
//             $(data).each(function(i){
//                 category_html += '<input name="se_category_id[]" type="checkbox" id="checkbox-moban" value="'+this.id+'"/>'+this.name;
//                 category_html += '<label for="checkbox-moban">&nbsp;</label>';
//             });
//             $('.se_category_id').html(category_html);
//         }else if(result.status == 0) {
//             $('.se_category_id').html(' ');
//         }
//     }, 'json');
// });
function index_add(title, url) {
    var index = layer.open({
        type: 2,
        title: title,
        content: url,
        cancel: function () {
            window.parent.location.reload();
        }
    });
    layer.full(index);
}


function index_edit(title, url) {
    var index = layer.open({
        type: 2,
        title: title,
        content: url
    });
    layer.full(index);
}

function index_look(title, url) {
    var index = layer.open({
        type: 2,
        title: title,
        content: url
    });
    layer.full(index);
}

/**
 * 编辑
 * @param url
 * @param title
 * @param w
 * @param h
 */
function edit(url, title, w, h) {
    layer_show(url, title, w, h);
}

/*弹出层*/

/*
 参数解释：
 title	标题
 url		请求的url
 id		需要操作的数据id
 w		弹出层宽度（缺省调默认值）
 h		弹出层高度（缺省调默认值）
 */
function add(title, url, w, h) {
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
        content: url,
        cancel: function () {
            window.parent.location.reload();
        }
    });
}

function muLay(title, url, w, h) {
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
        type: 2 //此处以iframe举例
        , title: title
        , area: [w + 'px', h + 'px']
        , shade: 0.2
        , maxmin: true
        , content: url
        , btn2: function () {
            layer.closeAll();
        }
        , zIndex: layer.zIndex //重点1
    });
}

//保存添加相关....
function save(url) {
    layer.confirm("确定保存提交吗?", function () {
        $.ajax({
            url: url,
            type: "post",
            dataType: "json",
            data: $("form").serialize(),//提交表单数据
            success: function (result) {
                if (result.status === 1) {
                    layer.msg(result.data, { icon: 1, time: 3000 }, function () {
                        window.parent.location.reload();
                    });
                } else {
                    layer.msg(result.data, { icon: 5, time: 2000 });
                }
            }
        })
    })
}

/**
 * 单个放入回收站
 * @param url
 *数据库里的字段status状态值为-1
 */
function recycle(url) {
    layer.confirm('确定进行该操作吗？', function () {
        $.ajax({
            url: url,
            type: "post",
            dataType: "json",
            success: function (result) {
                if (result.status === 1) {
                    layer.msg(result.data, { icon: 1, time: 1000 }, function () {
                        location.href = ''
                    });
                } else {
                    layer.msg(result.data, { icon: 5, time: 1000 }, function () {
                        location.href = ''
                    });
                }
            }
        })
    })
}

/**
 *  批量放入回收站
 * @param url
 * 状态值都为-1
 */
function allRecycle(url) {
    layer.confirm("确定要全部回收吗?", function () {
        var ids = $("input[name='id']:checked").serializeArray();
        /***
         $()是一个选择器：意思是说 将input标签里name=ID且是选中状态的项选择下来，serializeArray（）是将选中的项序列化获取数据
         */
        var postData = {};
        $(ids).each(function () {
            postData[this.value] = this.name
        });
        $.ajax({
            url: url,
            type: "post",
            dataType: "json",
            data: postData,
            success: function (result) {
                if (result.status === 1) {
                    layer.msg(result.data, { icon: 1, time: 1000 }, function () {
                        location.href = ''
                    });
                } else {
                    layer.msg(result.data, { icon: 5, time: 2000 });
                }
            }
        })
    })
}

/**
 * 点击恢复，状态值改变为
 * @param url
 */
function start(url) {
    //此处请求后台程序，下方是成功后的前台处理……
    $.ajax({
        url: url,
        type: "post",
        dataType: "json",
        success: function (result) {
            if (result.status === 1) {
                layer.msg(result.data, { icon: 1, time: 1000 }, function () {
                    location.href = ''
                });
            } else {
                layer.msg(result.data, { icon: 5, time: 3000 }, function () {
                    location.href = ''
                });
            }
        }
    });
}

/**
 * 点击下架，状态值变为0
 * @param url
 */
function stop(url) {
    layer.confirm('确认要停用吗？', function () {
        //此处请求后台程序，下方是成功后的前台处理……
        $.ajax({
            url: url,
            type: "post",
            dataType: "json",
            success: function (result) {
                if (result.status === 1) {
                    layer.msg(result.data, { icon: 1, time: 1500 }, function () {
                        location.href = ''
                    });
                } else {
                    layer.msg(result.data, { icon: 5, time: 1500 })
                }
            }
        });
    });
}

/**
 * 彻底删除
 * @param url
 */
function del(url) {
    layer.confirm('确定要彻底删除吗？', function () {
        $.ajax({
            url: url,
            type: "post",
            dataType: "json",
            success: function (result) {
                if (result.status === true) {
                    layer.msg(result.data, { icon: 1, time: 2000 }, function () {
                        location.replace(location.href)
                    });
                } else {
                    layer.msg(result.data, { icon: 5, time: 2000 });
                }
            }
        })
    })
}

/**
 * 批量彻底删除
 * @param url
 */
function delall(url) {
    layer.confirm("确定要全部彻底删除吗?", function () {
        var ids = $("input[name='id']:checked").serializeArray();
        /***
         $()是一个选择器：意思是说 将input标签里name=ID且是选中状态的项选择下来，serializeArray（）是将选中的项序列化获取数据
         */
        var postData = {};
        $(ids).each(function () {
            postData[this.value] = this.name
        });
        $.ajax({
            url: url,
            type: "post",
            dataType: "json",
            data: postData,
            success: function (result) {
                if (result.status === 1) {
                    layer.msg(result.data, { icon: 1, time: 2000 });
                    setTimeout("location.href=''", 2000)
                } else {
                    layer.msg(result.data, { icon: 5, time: 2000 });
                }
            }
        })
    })
}

/**
 * 搜索
 */
function build_html(url) {
    $.ajax({
        url: url,
        type: 'get',
        dataType: "json",
        success: function (result) {
            if (result.status === 1) {
                layer.msg(result.data, { icon: 1, time: 2000 });
            }
        }
    })
}

/**
 * 失去焦点时事件，提交input值,排序操作
 */
$('.listorder input').blur(function () {
    //获取主键ID
    var id = $(this).attr('attr-id');
    //获取置顶的值
    var listorder = $(this).val();
    var postData = {
        'id': id,
        'listorder': listorder
    };
    var url = SCOPE.sort_url;
    //抛送http
    $.post(url, postData, function (result) {
        if (result.status === 1) {
            // layer.msg(result.data, {icon: 1, time: 2000});
            dialog.OK(result.message, result.jump_url);
        } else {
            dialog.None(result.message, result.jump_url);
            // layer.msg(result.msg, {icon: 1, time: 2000});
            // location.href=result.data;
        }
    }, "json")
});

/**
 * 置顶修改排序操作
 */
function mark(obj) {
    var url = SCOPE.listorder_url;
    var id = $(obj).data("id");//id
    var type = $(obj).data("type");//类型
    var language_id = $("input#language").val(); //语言ID
    var map = $(obj).data("map"); // 分类ID
    var postData = {
        'id': id,
        'type': type,
        'language_id': language_id,
        'map': map
    };
    $.ajax({
        type: 'post',
        url: url,
        dataType: 'json',
        data: postData,
        success: function (result) {
            if (result.status === 1) {
                dialog.OK(result.data, result.jump_url);
            } else if (result.status === 0) {
                dialog.error(result.data, result.title, result.btn);
            } else if (result.status === -1) {
                dialog.toconfirm(result.data)
            }
        }
    })
}

/**
 * 上移下移
 */
function order(obj) {
    var url = SCOPE.order_url;
    var id = $(obj).data("id");
    var type = $(obj).data("type");
    var language_id = $("input#language").val();
    var postData = {
        'id': id,
        'language_id': language_id,
        'type': type
    };
    $.ajax({
        type: 'post',
        url: url,
        dataType: 'json',
        data: postData,
        success: function (result) {
            if (result.status === 1) {
                dialog.OK(result.message, result.jump_url);
            } else if (result.status === 0) {
                dialog.toconfirm(result.message);
            } else {
                dialog.toconfirm(result.message)
            }
        }
        // error:function () {
        //     dialog.toconfirm('没有权限')
        // }
    })
}

/***
 * 搜索靠前排序工具
 * @param obj
 */
function rankSearch(obj) {
    var url = SCOPE.rankSearch;
    var id = $(obj).data('id');
    var postData = {
        'id': id,
        'mark': 1
    };
    $.ajax({
        type: 'post',
        url: url,
        dataType: 'json',
        data: postData,
        success: function (result) {
            if (result.status === 1) {
                dialog.OK(result.message, result.jump_url)
            } else {
                dialog.error(result.message);
            }
        }
    })
}

// === 删除缓存 === //
$('.cleancache').click(function () {
    var url = $(this).data('url');
    layer.open({
        type: 2,
        title: "清空缓存",
        shade: [0],
        area: ['500px', '400px'],
        shift: 2,
        content: url
    });
});