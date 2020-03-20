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
    //推荐位
    Route::get('/Featured/index', 'Featured/index');
    Route::rule('/Featured/byStatus', 'Featured/byStatus','GET|POST');
    Route::get('/Featured/add', 'Featured/add');
    Route::get('/Featured/edit', 'Featured/edit', [], ['id' => '\d+']);
    Route::post('/Featured/save', 'Featured/save');
    //首页推荐产品
    Route::post('/images/save', 'Images/save');
    Route::rule('/images/byStatus', 'Images/byStatus','GET|POST');
    Route::get('/images/index', 'Images/index');
    Route::get('/images/add', 'Images/add');
    Route::get('/images/edit', 'Images/edit', [], ['id' => '\d+']);
    Route::get('/images/images_recycle', 'Images/images_recycle');
    Route::post('/Images/listorder', 'Images/listorder');
    Route::post('/images/del', 'Images/del');

    //产品分类路由
    Route::get('/category/index', 'Category/index');
    Route::get('/category/add', 'Category/add');
    Route::get('/Category/byStatus', 'wavlink/Category/byStatus');
    Route::get('/Category/edit', 'Category/edit', [], ['id' => '\d+']);
    //产品分类post路由
    Route::post('/category/save', 'Category/save');
    Route::post('/Category/byStatus', 'Category/byStatus');
    Route::post('/Category/sort', 'Category/sort');
    Route::post('/Category/del', 'Category/del');

    //产品管理
    Route::get('/product/index', 'Product/index');
    Route::get('/product/add', 'Product/add');
    Route::get('/product/product_edit', 'Product/product_edit', [], ['id' => '\d+']);
    Route::get('/product/product_recycle', 'Product/product_recycle');
    //产品post路由
    Route::post('/product/save', 'Product/save');
    Route::post('/product/byStatus', 'Product/byStatus');
    Route::post('/product/listorder', 'Product/listorder');
    Route::post('/product/sort', 'Product/sort');
    Route::post('/product/mark', 'Product/mark');

    //产品的购买链接
    Route::get('/product/shop_link/add', 'Product/add_shop_url', [], ['product_id' => '\d+']);
    Route::get('/product/shop_link/edit', 'Product/edit_shop_url', [], ['id' => '\d+']);
    Route::post('/product/shop_link/del', 'Product/del_shop_url', [], ['id' => '\d+']);
    Route::post('/product/shop_link/save', 'Product/save_shop_url');
    Route::get('/product/shop_link', 'Product/shop_link', [], ['product_id' => '\d+']);

    //文章管理
    Route::get('/article/index', 'Article/index');
    Route::get('/article/add', 'Article/add');
    Route::get('/article/edit', 'Article/edit', [], ['id' => '\d+']);
    Route::get('/article/article_recycle', 'Article/article_recycle');
    Route::post('/article/save', 'Article/save');
    Route::post('article/byStatus', 'Article/byStatus');
    Route::post('article/listorder', 'Article/listorder');
    //关于我们
    Route::get('/About/index', 'About/index');
    Route::get('/About/add', 'About/add');
    Route::get('/About/edit', 'About/edit', [], ['id' => '\d+']);
    Route::post('/About/save', 'About/save');
    Route::post('/About/byStatus', 'About/byStatus');


    //营销管理
    Route::get('/marketing/index', 'Marketing/index');
    Route::get('/marketing/add', 'Marketing/add');
    Route::get('/marketing/edit', 'Marketing/edit', [], ['id' => '\d+']);
    //营销管理POST路由
    Route::post('/marketing/save', 'Marketing/save');
    Route::post('/marketing/byStatus', 'Marketing/byStatus');
    Route::post('/marketing/del', 'Marketing/del');

    /***
     * 系统管理模块路由
     *
     */
    Route::get('/system/index', 'System/index');
    //语言
    /**
    * 语言切换
    */
    Route::get('/language/index', 'Language/index');
    Route::get('/language/add', 'Language/add');
    Route::get('/language/edit', 'Language/edit', [], ['id' => '\d+']);
    Route::get('/language/language_stop', 'Language/language_stop');
    Route::get('/language/:code', 'BaseAdmin/ChangeLanguage')->pattern(['code' => '[\w-]+']);
    Route::post('/language/save', 'Language/save');
    Route::post('/language/byStatus', 'Language/byStatus');

    //站点配置
    Route::get('/setting/index', 'Setting/index');
    Route::post('/Setting/save', 'Setting/save');

    //管理员列表
    Route::get('/manger/index', 'Manger/index');
    Route::get('/manger/add', 'Manger/add');
    Route::get('/manger/edit', 'Manger/edit', [], ['id' => '\d+']);
    Route::get('/manger/password', 'Manger/password', [], ['id' => '\d+']);
    //禁用的管理员
    Route::get('/manger/manger_stop', 'Manger/manger_stop');

    Route::post('/manger/save', 'Manger/saveEdit');
    Route::post('/manger/password', 'Manger/password');
    Route::post('/manger/byStatus', 'Manger/byStatus');

    //权限组
    Route::get('/auth_group/index', 'AuthGroup/index');
    Route::get('/auth_group/add', 'AuthGroup/add');
    Route::get('/auth_group/edit', 'AuthGroup/edit', [], ['id' => '\d+']);
    Route::post('/auth_group/save', 'AuthGroup/save');
    Route::post('/auth_group/byStatus', 'AuthGroup/byStatus');
    //权限
    Route::get('/auth_rule/index', 'AuthRule/index');
    Route::get('/auth_rule/add', 'AuthRule/add');
    Route::get('/auth_rule/edit', 'AuthRule/edit', [], ['id' => '\d+']);
    Route::post('/auth_rule/save', 'AuthRule/save');
    Route::post('/auth_rule/byStatus', 'AuthRule/byStatus');

    /**
     * service_category/index
     * 服务模块
     */
    //服务分类
    Route::get('/service/index', 'Service/index');
    Route::get('/service_category/index', 'ServiceCategory/index');
    Route::get('/service_category/add', 'ServiceCategory/add');
    Route::get('/service_category/edit', 'ServiceCategory/edit', [], ['id' => '\d+']);
    Route::post('/service_category/save', 'ServiceCategory/save');
    Route::post('/service_category/byStatus', 'ServiceCategory/byStatus');
    Route::post('/service_category/listorder', 'ServiceCategory/listorder');
    //文档管理
    Route::get('/document/index', 'Document/index');
    Route::get('/document/doc_recycle', 'Document/doc_recycle');
    Route::get('/document/add', 'Document/add');
    Route::get('/document/edit', 'Document/edit', [], ['id' => '\d+']);
    Route::post('/document/save', 'Document/save');
    Route::post('/Document/byStatus', 'Document/byStatus');
    Route::post('/Document/listorder', 'Document/listorder');

    //驱动管理
    Route::get('/drivers/index', 'Drivers/index');
    Route::post('/drivers/index', 'Drivers/index');
    Route::get('/drivers/recycle', 'Drivers/recycle');
    Route::get('/drivers/add', 'Drivers/add');
    Route::get('/drivers/edit', 'Drivers/edit', [], ['id' => '\d+']);
    Route::post('/drivers/save', 'Drivers/save');
    Route::post('/drivers/byStatus', 'Drivers/byStatus');
    Route::post('/Drivers/listorder', 'Drivers/listorder');
    Route::post('/Drivers/sort', 'Drivers/sort');

    //固件管理
    Route::get('/firmware/index', 'wavlink/firmware/index');
    Route::get('/firmware/add', 'wavlink/firmware/add');
    Route::get('/firmware/edit', 'wavlink/firmware/edit', [], ['id' => '\d+']);
    Route::get('/firmware/recycle', 'wavlink/firmware/recycle');
    //固件模块add保存
    Route::post('/firmware/add', 'Firmware/add');
    Route::post('/firmware/edit', 'Firmware/edit', [], ['id' => '\d+']);
    Route::post('/firmware/byStatus', 'Firmware/byStatus');
    Route::post('/firmware/del', 'Firmware/del');
    //说明书和文件
    Route::get('/manual/index', 'Manual/index');
    Route::get('/manual/add', 'Manual/add');
    Route::get('/manual/edit', 'Manual/edit', [], ['id' => '\d+']);
    Route::get('/manual/add_download', 'Manual/add_download', [], ['id' => '\d+']);
    Route::get('/manual/edit_download', 'Manual/edit_download', [], ['id' => '\d+', 'manual_id' => '\d+']);
    Route::get('/manual/del_download', 'Manual/del_download', [], ['id' => '\d+']);
    Route::post('/Manual/save', 'Manual/save');
    Route::post('/Manual/byStatus', 'Manual/byStatus');
    Route::post('/Manual/save_download', 'Manual/save_download');
    Route::post('/Manual/del_download', 'Manual/del_download');
    //视频'
    Route::get('/video/index', 'Video/index');
    Route::get('/video/video_recycle', 'Video/video_recycle');
    Route::get('/video/add', 'Video/add');
    Route::get('/video/edit', 'Video/edit', [], ['id' => '\d+']);
    Route::post('/Video/save', 'Video/save');
    Route::post('/video/byStatus', 'Video/byStatus');
    Route::post('/Video/listorder', 'Video/listorder');
    //留言管理
    Route::get('/guest_book/index', 'GuestBook/index');
    Route::get('/guest_book/export', 'GuestBook/export');
    Route::get('/guest_book/index_off', 'GuestBook/index_off');
    Route::get('/guest_book/look', 'GuestBook/look');
    Route::get('/guest_book/reply', 'GuestBook/reply');
    Route::get('/guest_book/send', 'GuestBook/send');
    Route::get('/guest_book/reply_look', 'GuestBook/reply_look');
    Route::post('/guest_book/send', 'GuestBook/send');
    //FAQ管理
    Route::get('/Faq/index', 'Faq/index');
    Route::get('/Faq/faq_recycle', 'Faq/faq_recycle');
    Route::get('/Faq/add', 'Faq/add');
    Route::get('/Faq/edit', 'Faq/edit', [], ['id' => '\d+']);
    Route::post('/Faq/save', 'Faq/save');
    Route::post('/faq/byStatus', 'Faq/byStatus');
    Route::post('/faq/listorder', 'Faq/listorder');

    //SN管理
    Route::get('/soft/index', 'Soft/index');
    Route::get('/soft/add', 'Soft/add');
    Route::get('/soft/edit', 'Soft/edit', [], ['id' => '\d+']);
    Route::get('/Soft/add_model', 'Soft/add_model');
    Route::get('/soft/saveID', 'Soft/saveID');
    Route::get('/soft/edit_model', 'Soft/edit_model', [], ['id' => '\d+']);
    Route::post('soft/edit', 'Soft/edit', [], ['id' => '\d+']);
    Route::post('Soft/byStatus', 'Soft/byStatus');
    //SN分类管理
    Route::get('/cate/index', 'Cate/index');
    Route::get('/cate/add', 'Cate/add');
    Route::post('/cate/index', 'Cate/index');
    Route::post('/cate/byStatus', 'Cate/byStatus');

    Route::get('/model/index', 'Model/index');
    Route::get('/model/add', 'Model/add');
    Route::get('/model/edit', 'Model/edit');
    Route::get('/model/add_soft', 'Model/add_soft');
    Route::get('/Model/saveID', 'Model/saveID');
    Route::get('/Model/edit_soft', 'Model/edit_soft', [], ['id' => '\d+']);
    Route::post('/Model/saveID', 'Model/saveID');
    Route::post('/model/edit', 'Model/edit');
    Route::post('/model/byStatus', 'Model/byStatus');

    Route::get('/sn/index', 'Sn/index');
    Route::get('/sn/add', 'Sn/add');
    Route::get('/sn/edit', 'Sn/edit', [], ['id' => '\d+']);
    Route::get('/old_sn/index', 'OldSn/index');
    Route::get('/old_sn/add', 'OldSn/add');
    Route::post('/old_sn/index', 'OldSn/index');
    Route::post('/sn/index', 'Sn/index');
    Route::post('/sn/byStatus', 'Sn/byStatus');

    //搜索索引管理
    Route::get('/search/index', 'Search/index');
    Route::get('/search/createProduct', 'Search/createProduct');
    Route::get('/search/createDriver$', 'Search/createDriver');
    Route::get('/search/createIndex$', 'Search/createIndex');
    Route::get('/search/getProduct$', 'Search/searchProduct');
    Route::get('/$', 'Index/index');
})->prefix('wavlink/');
