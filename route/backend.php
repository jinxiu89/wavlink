<?php

use think\facade\Route;
use think\facade\Config;

//get请求组
Route::group(Config::get('__BACKEND__'), function () {
    //登录登出
    Route::rule('/login/index$','wavlink/login/index','GET|POST')->name('admin_login');
    Route::rule('/login/logout$', 'wavlink/login/logout','GET|POST')->name('admin_logout');
    Route::rule('/login$', 'wavlink/login/index','GET|POST')->name('admin_login');
    /**
     * 清理缓存
     */
    Route::rule('/clean', 'Wavlink/Admin/clean','GET')->name('admin_clean');
    /***
     * 内容管理模块路由
     */
    //几个首页
    Route::get('/index', 'Index/index');
    Route::get('/content/index', 'Content/index');
    //分类url
    Route::get('/category/index', 'Category/index');
    Route::get('/category/add', 'Category/add');
    Route::get('/Category/byStatus', 'wavlink/Category/byStatus');
    Route::get('/Category/edit', 'Category/edit', [], ['id' => '\d+']);
    //推荐位
    Route::get('/Featured/index', 'Featured/index');
    Route::get('/Featured/byStatus', 'Featured/byStatus');
    Route::get('/Featured/add', 'Featured/add');
    Route::get('/Featured/edit', 'Featured/edit', [], ['id' => '\d+']);
    Route::post('/Featured/save', 'Featured/save');
    //首页推荐产品
    Route::get('/images/index', 'Images/index');
    Route::get('/images/add', 'Images/add');
    Route::get('/images/edit', 'Images/edit', [], ['id' => '\d+']);
    Route::get('/images/images_recycle', 'Images/images_recycle');
    //产品管理
    Route::get('/product/index', 'Product/index');
    Route::get('/product/add', 'Product/add');
    Route::get('/product/product_edit', 'Product/product_edit', [], ['id' => '\d+']);
    Route::get('/product/product_recycle', 'Product/product_recycle');
    //产品的购买链接
    Route::get('/product/shop_link/add', 'Product/add_shop_url', [], ['product_id' => '\d+']);
    Route::get('/product/shop_link/edit', 'Product/edit_shop_url', [], ['id' => '\d+']);
    Route::post('/product/shop_link/del', 'Product/del_shop_url', [], ['id' => '\d+']);
    Route::post('/product/shop_link/save', 'Product/save_shop_url');
    Route::get('/product/shop_link', 'Product/shop_link', [], ['product_id' => '\d+']);


    //营销管理
    Route::get('/marketing/index', 'Marketing/index');
    Route::get('/marketing/add', 'Marketing/add');
    Route::get('/marketing/edit', 'Marketing/edit', [], ['id' => '\d+']);

    /***
     * 系统管理模块路由
     *
     */
    Route::get('/system/index', 'System/index');
    //语言
    Route::get('/language/index', 'Language/index');
    Route::get('/language/add', 'Language/add');
    Route::get('/language/edit', 'Language/edit', [], ['id' => '\d+']);
    Route::get('/language/language_stop', 'Language/language_stop');
    /**
     * 语言切换
     */
    Route::get('/language/:code', 'BaseAdmin/ChangeLanguage')->pattern(['code' => '[\w-]+']);
    //关于我们
    Route::get('/About/index', 'About/index');
    Route::get('/About/add', 'About/add');
    Route::get('/About/edit', 'About/edit', [], ['id' => '\d+']);
    //站点配置
    Route::get('/setting/index', 'Setting/index');
    //管理员列表
    Route::get('/manger/index', 'Manger/index');
    Route::get('/manger/add', 'Manger/add');
    Route::get('/manger/edit', 'Manger/edit', [], ['id' => '\d+']);
    Route::get('/manger/password', 'Manger/password', [], ['id' => '\d+']);
    //禁用的管理员
    Route::get('/manger/manger_stop', 'Manger/manger_stop');

    //权限组
    Route::get('/auth_group/index', 'AuthGroup/index');
    Route::get('/auth_group/add', 'AuthGroup/add');
    Route::get('/auth_group/edit', 'AuthGroup/edit', [], ['id' => '\d+']);
    //权限
    Route::get('/auth_rule/index', 'AuthRule/index');
    Route::get('/auth_rule/add', 'AuthRule/add');
    Route::get('/auth_rule/edit', 'AuthRule/edit', [], ['id' => '\d+']);
    /**
     * service_category/index
     * 服务模块
     */
    //服务分类
    Route::get('/service/index', 'Service/index');
    Route::get('/service_category/index', 'ServiceCategory/index');
    Route::get('/service_category/add', 'ServiceCategory/add');
    Route::get('/service_category/edit', 'ServiceCategory/edit', [], ['id' => '\d+']);
    //文章管理
    Route::get('/article/index', 'Article/index');
    Route::get('/article/add', 'Article/add');
    Route::get('/article/edit', 'Article/edit', [], ['id' => '\d+']);
    Route::get('/article/article_recycle', 'Article/article_recycle');
    //文档管理
    Route::get('/document/index', 'Document/index');
    Route::get('/document/doc_recycle', 'Document/doc_recycle');
    Route::get('/document/add', 'Document/add');
    Route::get('/document/edit', 'Document/edit', [], ['id' => '\d+']);
    //驱动管理
    Route::get('/drivers/index', 'Drivers/index');
    Route::post('/drivers/index', 'Drivers/index');
    Route::get('/drivers/recycle', 'Drivers/recycle');
    Route::get('/drivers/add', 'Drivers/add');
    Route::get('/drivers/edit', 'Drivers/edit', [], ['id' => '\d+']);
    //固件管理
    Route::get('/firmware/index', 'wavlink/firmware/index');
    Route::get('/firmware/add', 'wavlink/firmware/add');
    Route::get('/firmware/edit', 'wavlink/firmware/edit', [], ['id' => '\d+']);
    Route::get('/firmware/recycle', 'wavlink/firmware/recycle');
    //说明书和文件
    Route::get('/manual/index', 'Manual/index');
    Route::get('/manual/add', 'Manual/add');
    Route::get('/manual/edit', 'Manual/edit', [], ['id' => '\d+']);
    Route::get('/manual/add_download', 'Manual/add_download', [], ['id' => '\d+']);
    Route::get('/manual/edit_download', 'Manual/edit_download', [], ['id' => '\d+', 'manual_id' => '\d+']);
    Route::get('/manual/del_download', 'Manual/del_download', [], ['id' => '\d+']);
    //视频'
    Route::get('/video/index', 'Video/index');
    Route::get('/video/video_recycle', 'Video/video_recycle');
    Route::get('/video/add', 'Video/add');
    Route::get('/video/edit', 'Video/edit', [], ['id' => '\d+']);
    //留言管理
    Route::get('/guest_book/index', 'GuestBook/index');
    Route::get('/guest_book/export', 'GuestBook/export');
    Route::get('/guest_book/index_off', 'GuestBook/index_off');
    Route::get('/guest_book/look', 'GuestBook/look');
    Route::get('/guest_book/reply', 'GuestBook/reply');
    Route::get('/guest_book/send', 'GuestBook/send');
    Route::get('/guest_book/reply_look', 'GuestBook/reply_look');
    //FAQ管理
    Route::get('/Faq/index', 'Faq/index');
    Route::get('/Faq/faq_recycle', 'Faq/faq_recycle');
    Route::get('/Faq/add', 'Faq/add');
    Route::get('/Faq/edit', 'Faq/edit', [], ['id' => '\d+']);
    //SN管理
    Route::get('/soft/index', 'Soft/index');
    Route::get('/soft/add', 'Soft/add');
    Route::get('/soft/edit', 'Soft/edit', [], ['id' => '\d+']);
    Route::get('/Soft/add_model', 'Soft/add_model');
    Route::get('/soft/saveID', 'Soft/saveID');
    Route::get('/soft/edit_model', 'Soft/edit_model', [], ['id' => '\d+']);
    Route::get('/cate/index', 'Cate/index');
    Route::get('/cate/add', 'Cate/add');
    Route::get('/model/index', 'Model/index');
    Route::get('/model/add', 'Model/add');
    Route::get('/model/edit', 'Model/edit');
    Route::get('/model/add_soft', 'Model/add_soft');
    Route::get('/Model/saveID', 'Model/saveID');
    Route::get('/Model/edit_soft', 'Model/edit_soft', [], ['id' => '\d+']);
    Route::get('/sn/index', 'Sn/index');
    Route::get('/sn/add', 'Sn/add');
    Route::get('/sn/edit', 'Sn/edit', [], ['id' => '\d+']);
    Route::get('/old_sn/index', 'OldSn/index');
    Route::get('/old_sn/add', 'OldSn/add');
    //搜索索引管理
    Route::get('/search/index', 'Search/index');
    Route::get('/search/createProduct', 'Search/createProduct');
    Route::get('/search/createDriver$', 'Search/createDriver');
    Route::get('/search/createIndex$', 'Search/createIndex');
    Route::get('/search/getProduct$', 'Search/searchProduct');
    Route::get('/$', 'Index/index');
})->prefix('wavlink/');

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
    Route::post('/Images/listorder', 'wavlink/images/listorder');
    Route::post('/product/listorder', 'wavlink/product/listorder');
    Route::post('/product/sort', 'wavlink/product/sort');
    Route::post('/product/mark', 'wavlink/product/mark');
    /**
     * 系统管理模块Post请求路由
     * 关于Url 的带杠和不带杠 需要再找时间一一测试
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
    Route::post('Manual/byStatus', 'wavlink/Manual/byStatus');
    Route::post('cate/index', 'wavlink/cate/index');
    Route::post('sn/index', 'wavlink/sn/index');
    Route::post('old_sn/index', 'wavlink/OldSn/index');
    Route::post('Manual/save_download', 'wavlink/Manual/save_download');
    Route::post('Manual/del_download', 'wavlink/Manual/del_download');
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

    Route::post('service_category/listorder', 'wavlink/ServiceCategory/listorder');
    Route::post('article/listorder', 'wavlink/Article/listorder');
    Route::post('Document/listorder', 'wavlink/Document/listorder');
    Route::post('Drivers/listorder', 'wavlink/Drivers/listorder');
    Route::post('Drivers/sort', 'wavlink/Drivers/sort');
    Route::post('Video/listorder', 'wavlink/Video/listorder');
    Route::post('faq/listorder', 'wavlink/faq/listorder');
    //固件模块add保存
    Route::post('firmware/add', 'wavlink/firmware/add');
    Route::post('firmware/edit', 'wavlink/firmware/edit', [], ['id' => '\d+']);
    Route::post('firmware/byStatus', 'wavlink/firmware/byStatus');
    Route::post('firmware/del', 'wavlink/firmware/del');
});
