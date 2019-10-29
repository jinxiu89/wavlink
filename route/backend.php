<?php
use think\facade\Route;
use think\facade\Config;

//get请求组
Route::group(Config::get('__BACKEND__'), function () {
    //登录登出
    Route::get('/login/index', 'wavlink/login/index');
    Route::get('/login/logout', 'wavlink/login/logout');
    Route::get('/login', 'wavlink/login/index');
    /***
     * 内容管理模块路由
     */
    //几个首页
    Route::get('/index', 'wavlink/index/index');
    Route::get('/content/index', 'wavlink/content/index');
    //分类url
    Route::get('/category/index', 'wavlink/Category/index');
    Route::get('/category/add', 'wavlink/Category/add');
    Route::get('/Category/byStatus', 'wavlink/Category/byStatus');
    Route::get('/Category/edit', 'wavlink/Category/edit', [], ['id' => '\d+']);
    //推荐位
    Route::get('/Featured/index', 'wavlink/Featured/index');
    Route::get('/Featured/byStatus', 'wavlink/Featured/byStatus');
    Route::get('/Featured/add', 'wavlink/Featured/add');
    Route::get('/Featured/edit', 'wavlink/Featured/edit', [], ['id' => '\d+']);
    //首页推荐产品
    Route::get('/images/index', 'wavlink/images/index');
    Route::get('/images/add', 'wavlink/images/add');
    Route::get('/images/edit', 'wavlink/images/edit', [], ['id' => '\d+']);
    Route::get('/images/images_recycle', 'wavlink/images/images_recycle');
    //产品管理
    Route::get('/product/index', 'wavlink/product/index');
    Route::get('/product/add', 'wavlink/product/add');
    Route::get('/product/product_edit', 'wavlink/product/product_edit', [], ['id' => '\d+']);
    Route::get('/product/product_recycle', 'wavlink/product/product_recycle');
    //营销管理
    Route::get('/marketing/index', 'wavlink/marketing/index');
    Route::get('/marketing/add', 'wavlink/Marketing/add');
    Route::get('/marketing/edit', 'wavlink/Marketing/edit', [], ['id' => '\d+']);
    /***
     * 系统管理模块路由
     *
     */
    Route::get('/system/index', 'wavlink/System/index');
    //语言
    Route::get('/language/index', 'wavlink/Language/index');
    Route::get('/language/add', 'wavlink/Language/add');
    Route::get('/language/edit', 'wavlink/Language/edit', [], ['id' => '\d+']);
    Route::get('/language/language_stop', 'wavlink/Language/language_stop');
    //关于我们
    Route::get('/About/index', 'wavlink/About/index');
    Route::get('/About/add', 'wavlink/About/add');
    Route::get('/About/edit', 'wavlink/About/edit', [], ['id' => '\d+']);
    //站点配置
    Route::get('setting/index', 'wavlink/Setting/index');
    //管理员列表
    Route::get('manger/index', 'wavlink/manger/index');
    Route::get('manger/add', 'wavlink/manger/add');
    Route::get('manger/edit', 'wavlink/manger/edit', [], ['id' => '\d+']);
    Route::get('manger/password', 'wavlink/manger/password', [], ['id' => '\d+']);
    //禁用的管理员
    Route::get('manger/manger_stop', 'wavlink/manger/manger_stop');

    //权限组
    Route::get('auth_group/index', 'wavlink/AuthGroup/index');
    Route::get('auth_group/add', 'wavlink/AuthGroup/add');
    Route::get('auth_group/edit', 'wavlink/AuthGroup/edit', [], ['id' => '\d+']);
    //权限
    Route::get('auth_rule/index', 'wavlink/AuthRule/index');
    Route::get('auth_rule/add', 'wavlink/AuthRule/add');
    Route::get('auth_rule/edit', 'wavlink/AuthRule/edit', [], ['id' => '\d+']);
    /**
     * service_category/index
     * 服务模块
     */
    //服务分类
    Route::get('service/index', 'wavlink/service/index');
    Route::get('service_category/index', 'wavlink/ServiceCategory/index');
    Route::get('service_category/add', 'wavlink/ServiceCategory/add');
    Route::get('service_category/edit', 'wavlink/ServiceCategory/edit', [], ['id' => '\d+']);
    //文章管理
    Route::get('article/index', 'wavlink/article/index');
    Route::get('article/add', 'wavlink/article/add');
    Route::get('article/edit', 'wavlink/article/edit', [], ['id' => '\d+']);
    Route::get('article/article_recycle', 'wavlink/article/article_recycle');
    //文档管理
    Route::get('document/index', 'wavlink/document/index');
    Route::get('document/doc_recycle', 'wavlink/document/doc_recycle');
    Route::get('document/add', 'wavlink/document/add');
    Route::get('document/edit', 'wavlink/document/edit', [], ['id' => '\d+']);
    //驱动管理
    Route::get('drivers/index', 'wavlink/drivers/index');
    Route::get('drivers/recycle', 'wavlink/drivers/recycle');
    Route::get('drivers/add', 'wavlink/drivers/add');
    Route::get('drivers/edit', 'wavlink/drivers/edit', [], ['id' => '\d+']);
    //说明书和文件
    Route::get('manual/index', 'wavlink/manual/index');
    Route::get('manual/add', 'wavlink/manual/add');
    Route::get('manual/edit', 'wavlink/manual/edit', [], ['id' => '\d+']);
    Route::get('manual/add_download', 'wavlink/Manual/add_download', [], ['id' => '\d+']);
    Route::get('manual/edit_download', 'wavlink/manual/edit_download', [], ['id' => '\d+', 'manual_id' => '\d+']);
    Route::get('manual/del_download', 'wavlink/Manual/del_download', [], ['id' => '\d+']);
    //视频
    Route::get('video/index', 'wavlink/video/index');
    Route::get('video/video_recycle', 'wavlink/video/video_recycle');
    Route::get('video/add', 'wavlink/video/add');
    Route::get('video/edit', 'wavlink/video/edit', [], ['id' => '\d+']);
    //留言管理
    Route::get('guest_book/index', 'wavlink/GuestBook/index');
    Route::get('guest_book/export', 'wavlink/GuestBook/export');
    Route::get('guest_book/index_off', 'wavlink/GuestBook/index_off');
    Route::get('guest_book/look', 'wavlink/GuestBook/look');
    Route::get('guest_book/reply', 'wavlink/GuestBook/reply');
    Route::get('guest_book/send', 'wavlink/GuestBook/send');
    Route::get('guest_book/reply_look', 'wavlink/GuestBook/reply_look');
    //FAQ管理
    Route::get('/Faq/index', 'wavlink/Faq/index');
    Route::get('/Faq/faq_recycle', 'wavlink/Faq/faq_recycle');
    Route::get('/Faq/add', 'wavlink/Faq/add');
    Route::get('/Faq/edit', 'wavlink/Faq/edit', [], ['id' => '\d+']);
    //SN管理
    Route::get('soft/index', 'wavlink/soft/index');
    Route::get('soft/add', 'wavlink/soft/add');
    Route::get('soft/edit', 'wavlink/soft/edit', [], ['id' => '\d+']);
    Route::get('Soft/add_model', 'wavlink/Soft/add_model');
    Route::get('Soft/saveID', 'wavlink/Soft/saveID');
    Route::get('Soft/edit_model', 'wavlink/Soft/edit_model', [], ['id' => '\d+']);
    Route::get('cate/index', 'wavlink/cate/index');
    Route::get('cate/add', 'wavlink/cate/add');
    Route::get('model/index', 'wavlink/model/index');
    Route::get('model/add', 'wavlink/model/add');
    Route::get('model/edit', 'wavlink/model/edit');
    Route::get('model/add_soft', 'wavlink/model/add_soft');
    Route::get('Model/saveID', 'wavlink/Model/saveID');
    Route::get('Model/edit_soft', 'wavlink/Model/edit_soft', [], ['id' => '\d+']);
    Route::get('sn/index', 'wavlink/sn/index');
    Route::get('sn/add', 'wavlink/sn/add');
    Route::get('sn/edit', 'wavlink/sn/edit', [], ['id' => '\d+']);
    Route::get('old_sn/index', 'wavlink/OldSn/index');
    Route::get('old_sn/add', 'wavlink/OldSn/add');
    Route::get('/','wavlink/index/index');
});

//post请求组
Route::group(Config::get('__BACKEND__'), function () {
    /***
     * 登录请求post接口路由
     */
    Route::post('/login/index', 'wavlink/login/index');
    /***
     * 内容模块POST请求接口
     */
    Route::post('/category/save', 'wavlink/Category/save');
    Route::post('/Featured/save', 'wavlink/Featured/save');
    Route::post('/images/save', 'wavlink/Images/save');
    Route::post('/product/save', 'wavlink/product/save');
    Route::post('/marketing/save', 'wavlink/Marketing/save');
    Route::post('/Category/byStatus', 'wavlink/Category/byStatus');
    Route::post('/Category/sort', 'wavlink/Category/sort');
    Route::post('/Category/del', 'wavlink/Category/del');
    Route::post('/Featured/byStatus', 'wavlink/Featured/byStatus');
    Route::post('/images/byStatus', 'wavlink/Images/byStatus');
    Route::post('/images/del', 'wavlink/Images/del');
    Route::post('/product/byStatus', 'wavlink/product/byStatus');
    Route::post('/marketing/byStatus', 'wavlink/Marketing/byStatus');
    Route::post('/marketing/del', 'wavlink/Marketing/del');
    Route::post('/Images/listorder', 'wavlink/Images/listorder');
    Route::post('/product/listorder', 'wavlink/product/listorder');
    Route::post('/product/sort', 'wavlink/product/sort');
    Route::post('/product/mark', 'wavlink/product/mark');
    /**
     * 系统管理模块Post请求路由
     */
    Route::post('/language/save', 'wavlink/Language/save');
    Route::post('/language/byStatus', 'wavlink/Language/byStatus');
    Route::post('/About/save', 'wavlink/About/save');
    Route::post('/Setting/save', 'wavlink/Setting/save');
    Route::post('/manger/save', 'wavlink/manger/saveEdit');
    Route::post('/manger/password', 'wavlink/manger/password');
    Route::post('/auth_group/save', 'wavlink/AuthGroup/save');
    Route::post('/auth_rule/save', 'wavlink/AuthRule/save');
    Route::post('/About/byStatus', 'wavlink/About/byStatus');
    Route::post('/manger/byStatus', 'wavlink/manger/byStatus');
    Route::post('/auth_group/byStatus', 'wavlink/AuthGroup/byStatus');
    Route::post('/auth_rule/byStatus', 'wavlink/AuthRule/byStatus');
    /**
     * 服务模块Post请求
     */
    Route::post('service_category/save', 'wavlink/ServiceCategory/save');
    Route::post('article/save', 'wavlink/article/save');
    Route::post('document/save', 'wavlink/document/save');
    Route::post('drivers/save', 'wavlink/drivers/save');
    Route::post('Manual/save', 'wavlink/Manual/save');
    Route::post('cate/index', 'wavlink/cate/index');
    Route::post('sn/index', 'wavlink/sn/index');
    Route::post('old_sn/index', 'wavlink/OldSn/index');
    Route::post('Manual/save_download', 'wavlink/Manual/save_download');
    Route::post('Video/save', 'wavlink/Video/save');
    Route::post('Faq/save', 'wavlink/Faq/save');
    Route::post('guest_book/send', 'wavlink/GuestBook/send');
    Route::post('soft/edit', 'wavlink/soft/edit', [], ['id' => '\d+']);
    Route::post('Model/saveID', 'wavlink/Model/saveID');
    Route::post('model/edit', 'wavlink/model/edit');

    Route::post('service_category/byStatus', 'wavlink/ServiceCategory/byStatus');
    Route::post('article/byStatus', 'wavlink/article/byStatus');
    Route::post('Document/byStatus', 'wavlink/Document/byStatus');
    Route::post('drivers/byStatus', 'wavlink/Drivers/byStatus');
    Route::post('video/byStatus', 'wavlink/video/byStatus');
    Route::post('faq/byStatus', 'wavlink/faq/byStatus');
    Route::post('Soft/byStatus', 'wavlink/Soft/byStatus');
    Route::post('cate/byStatus', 'wavlink/cate/byStatus');
    Route::post('model/byStatus', 'wavlink/model/byStatus');
    Route::post('sn/byStatus', 'wavlink/sn/byStatus');

    Route::post('/service_category/listorder', 'wavlink/ServiceCategory/listorder');
    Route::post('/article/listorder', 'wavlink/Article/listorder');
    Route::post('/Document/listorder', 'wavlink/Document/listorder');
    Route::post('/Drivers/listorder', 'wavlink/Drivers/listorder');
    Route::post('/Video/listorder', 'wavlink/Video/listorder');
    Route::post('/faq/listorder', 'wavlink/faq/listorder');
});
