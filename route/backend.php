<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/9/11
 * Time: 10:16
 * 路由说明
 * 1、不管是路由分组 还是 不分组， 匹配规则都是由小到大，一些宽泛的路由规则需要放到最后，防止有些小的路由规则被劫持
 * 2、这里设置的路由都没有name()属性，但严格意义上讲需要给一个name属性,后面使用url()函数时方便使用
 */

use think\facade\Route;
use think\facade\Config;

//get请求组
Route::group(Config::get('__BACKEND__'), function () {
    //登录登出
    Route::rule('/login/index$', 'wavlink/login/index', 'GET|POST')->name('admin_login');
    Route::rule('/login/logout$', 'wavlink/login/logout', 'GET|POST')->name('admin_logout');
    Route::rule('/login$', 'wavlink/login/index', 'GET|POST')->name('admin_login');
    /**
     * 清理缓存
     */
    Route::rule('/clean', 'BaseAdmin/clean', 'GET')->name('admin_clean');
    /***
     * 内容管理模块路由
     */
    //几个首页
    Route::get('/index', 'Index/index');
    Route::get('/content/index', 'Content.Content/index');
    //推荐位
    Route::get('/Featured/index', 'Content.Featured/index')->name('featured_list');
    Route::rule('/Featured/byStatus', 'Content.Featured/byStatus', 'GET|POST');
    Route::get('/Featured/add', 'Content.Featured/add');
    Route::get('/Featured/edit', 'Content.Featured/edit', [], ['id' => '\d+']);
    Route::post('/Featured/save', 'Content.Featured/save');
    //首页推荐产品
    Route::post('/images/save', 'Content.Images/save');
    Route::rule('/images/byStatus', 'Content.Images/byStatus', 'GET|POST');
    Route::get('/images/index', 'Content.Images/index');
    Route::get('/images/add', 'Content.Images/add');
    Route::get('/images/edit', 'Content.Images/edit', [], ['id' => '\d+']);
    Route::get('/images/images_recycle', 'Content.Images/images_recycle');
    Route::post('/Images/listorder', 'Content.Images/listorder');
    Route::post('/images/del', 'Content.Images/del');

    //产品分类路由
    Route::get('/category/index', 'Content.Category/index');
    Route::get('/category/add', 'Content.Category/add');
    Route::get('/Category/byStatus', 'Content.Category/byStatus')->name('changeCategoryStatus');
    Route::get('/Category/edit', 'Content.Category/edit', [], ['id' => '\d+']);
    //产品分类post路由
    Route::post('/category/save', 'Content.Category/save');
    Route::post('/Category/byStatus', 'Content.Category/byStatus');
    Route::post('/Category/sort', 'Content.Category/sort');
    Route::post('/Category/del', 'Content.Category/del');

    //产品管理
    Route::get('/product/index', 'Content.Product/index');
    Route::get('/product/add', 'Content.Product/add');
    Route::get('/product/product_edit', 'Content.Product/product_edit', [], ['id' => '\d+']);
    Route::get('/product/product_recycle', 'Content.Product/product_recycle');
    //产品post路由
    Route::post('/product/save', 'Content.Product/save');
    Route::post('/product/byStatus', 'Content.Product/byStatus');
    Route::post('/product/listorder', 'Content.Product/listorder');
    Route::post('/product/sort', 'Content.Product/sort');
    Route::post('/product/mark', 'Content.Product/mark');

    //产品的购买链接
    Route::get('/product/shop_link/add', 'Content.Product/add_shop_url', [], ['product_id' => '\d+']);
    Route::get('/product/shop_link/edit', 'Content.Product/edit_shop_url', [], ['id' => '\d+']);
    Route::post('/product/shop_link/del', 'Content.Product/del_shop_url', [], ['id' => '\d+']);
    Route::post('/product/shop_link/save', 'Content.Product/save_shop_url');
    Route::get('/product/shop_link', 'Content.Product/shop_link', [], ['product_id' => '\d+']);

    //文章管理
    Route::get('/article/index', 'Content.Article/index');
    Route::get('/article/add', 'Content.Article/add');
    Route::get('/article/edit', 'Content.Article/edit', [], ['id' => '\d+']);
    Route::get('/article/article_recycle', 'Content.Article/article_recycle');
    Route::post('/article/save', 'Content.Article/save');
    Route::post('article/byStatus', 'Content.Article/byStatus');
    Route::post('article/listorder', 'Content.Article/listorder');
    //关于我们
    Route::get('/About/index', 'Content.About/index');
    Route::get('/About/add', 'Content.About/add');
    Route::get('/About/edit', 'Content.About/edit', [], ['id' => '\d+']);
    Route::post('/About/save', 'Content.About/save');
    Route::post('/About/byStatus', 'Content.About/byStatus');


    /**
     * 营销管理
     */
    Route::rule('/marketing/customer/index$', 'Marketing.Customer/index')->name('marketing_customer_index');
    Route::get('/email/template/index$', 'Marketing.Email/index')->name('email_template');
    //营销管理
    Route::get('/marketing/index$', 'Marketing.OnePage/index');
    Route::get('/marketing/add$', 'Marketing.OnePage/add');
    Route::get('/marketing/edit$', 'Marketing.OnePage/edit', [], ['id' => '\d+']);
    //营销管理POST路由
    Route::post('/marketing/save$', 'Marketing.OnePage/save');
    Route::post('/marketing/byStatus$', 'Marketing.OnePage/byStatus');
    Route::post('/marketing/del$', 'Marketing.OnePage/del');


    /***
     * 系统管理模块路由
     *
     */
    Route::get('/system/index', 'System.System/index');
    //语言
    /**
     * 语言切换
     */
    Route::get('/language/index', 'System.Language/index');
    Route::get('/language/add', 'System.Language/add');
    Route::get('/language/edit', 'System.Language/edit', [], ['id' => '\d+']);
    Route::get('/language/language_stop', 'System.Language/language_stop');
    Route::get('/language/:code', 'BaseAdmin/ChangeLanguage')->pattern(['code' => '[\w-]+']);
    Route::post('/language/save', 'System.Language/save');
    Route::post('/language/byStatus', 'System.Language/byStatus');

    //站点配置
    Route::get('/setting/index', 'System.Setting/index');
    Route::post('/Setting/save', 'System.Setting/save');

    //管理员列表
    Route::get('/manger/index', 'System.Manger/index');
    Route::get('/manger/add', 'System.Manger/add');
    Route::post('/manger/add_manager', 'System.Manger/addManger')->name('add_manger');
    Route::get('/manger/edit', 'System.Manger/edit', [], ['id' => '\d+']);
    Route::get('/manger/password', 'System.Manger/password', [], ['id' => '\d+']);
    //禁用的管理员
    Route::get('/manger/manger_stop', 'System.Manger/manger_stop');

    Route::post('/manger/save', 'System.Manger/saveEdit');
    Route::post('/manger/password', 'System.Manger/password');
    Route::post('/manger/byStatus', 'System.Manger/byStatus');

    //权限组
    Route::get('/auth_group/index', 'System.AuthGroup/index');
    Route::get('/auth_group/add', 'System.AuthGroup/add');
    Route::get('/auth_group/edit', 'System.AuthGroup/edit', [], ['id' => '\d+']);
    Route::post('/auth_group/save', 'System.AuthGroup/save');
    Route::post('/auth_group/byStatus', 'System.AuthGroup/byStatus');
    //权限
    Route::get('/auth_rule/index', 'System.AuthRule/index');
    Route::get('/auth_rule/add', 'System.AuthRule/add');
    Route::get('/auth_rule/edit', 'System.AuthRule/edit', [], ['id' => '\d+']);
    Route::post('/auth_rule/save', 'System.AuthRule/save');
    Route::post('/auth_rule/byStatus', 'System.AuthRule/byStatus');

    /**
     * service_category/index
     * 服务模块
     */
    //服务分类
    Route::get('/service/index', 'Service.Service/index');
    Route::get('/service_category/index', 'Service.ServiceCategory/index');
    Route::get('/service_category/add', 'Service.ServiceCategory/add');
    Route::get('/service_category/edit', 'Service.ServiceCategory/edit', [], ['id' => '\d+']);
    Route::post('/service_category/save', 'Service.ServiceCategory/save');
    Route::post('/service_category/byStatus', 'Service.ServiceCategory/byStatus');
    Route::post('/service_category/listorder', 'Service.ServiceCategory/listorder');
    //文档管理
    Route::get('/document/index', 'Document/index');
    Route::get('/document/doc_recycle', 'Document/doc_recycle');
    Route::get('/document/add', 'Document/add');
    Route::get('/document/edit', 'Document/edit', [], ['id' => '\d+']);
    Route::post('/document/save', 'Document/save');
    Route::post('/Document/byStatus', 'Document/byStatus');
    Route::post('/Document/listorder', 'Document/listorder');

    //驱动分类
    Route::get('/drivers/Category$', 'Service.DriversCategory/index')->name('driver_category_index');
    Route::rule('/drivers/Category/add', 'Service.DriversCategory/add', 'GET|POST')->name('add_driver_category');
    Route::rule('/drivers/Category/edit', 'Service.DriversCategory/edit', 'GET|POST')->name('edit_driver_category');
    Route::rule('/drivers/Category/byStatus', 'Service.DriversCategory/ByStatus', 'GET|POST')->name('del_driver_category');
    //驱动管理
    Route::get('/drivers/index', 'Service.Drivers/index');
    Route::post('/drivers/index', 'Service.Drivers/index');
    Route::get('/drivers/recycle', 'Service.Drivers/recycle');
    Route::get('/drivers/add', 'Service.Drivers/add');
    Route::get('/drivers/edit', 'Service.Drivers/edit', [], ['id' => '\d+']);
    Route::post('/drivers/save', 'Service.Drivers/save');
    Route::post('/drivers/byStatus', 'Service.Drivers/byStatus');
    Route::post('/Drivers/listorder', 'Service.Drivers/listorder');
    Route::post('/Drivers/sort', 'Service.Drivers/sort');

    //固件管理
    Route::get('/firmware/index', 'Service.Firmware/index');
    Route::get('/firmware/add', 'Service.Firmware/add');
    Route::get('/firmware/edit', 'Service.Firmware/edit', [], ['id' => '\d+']);
    Route::get('/firmware/recycle', 'Service.Firmware/recycle');
    //固件模块add保存
    Route::post('/firmware/add', 'Service.Firmware/add');
    Route::post('/firmware/edit', 'Service.Firmware/edit', [], ['id' => '\d+']);
    Route::post('/firmware/byStatus', 'Service.Firmware/byStatus');
    Route::post('/firmware/del', 'Service.Firmware/del');
    //说明书和文件
    Route::get('/manual/index', 'Service.Manual/index');
    Route::get('/manual/add', 'Service.Manual/add');
    Route::get('/manual/edit', 'Service.Manual/edit', [], ['id' => '\d+']);
    Route::get('/manual/add_download', 'Service.Manual/add_download', [], ['id' => '\d+']);
    Route::get('/manual/edit_download', 'Service.Manual/edit_download', [], ['id' => '\d+', 'manual_id' => '\d+']);
    Route::get('/manual/del_download', 'Service.Manual/del_download', [], ['id' => '\d+']);
    Route::post('/Manual/save', 'Service.Manual/save');
    Route::post('/Manual/byStatus', 'Service.Manual/byStatus');
    Route::post('/Manual/save_download', 'Service.Manual/save_download');
    Route::post('/Manual/del_download', 'Service.Manual/del_download');
    //视频'
    Route::get('/video/index', 'Service.Video/index');
    Route::get('/video/video_recycle', 'Service.Video/video_recycle');
    Route::get('/video/add', 'Service.Video/add');
    Route::get('/video/edit', 'Service.Video/edit', [], ['id' => '\d+']);
    Route::post('/Video/save', 'Service.Video/save');
    Route::post('/video/byStatus', 'Service.Video/byStatus');
    Route::post('/Video/listorder', 'Service.Video/listorder');
    //留言管理
    Route::get('/guest_book/index', 'Service.GuestBook/index');
    Route::get('/guest_book/export', 'Service.GuestBook/export');
    Route::get('/guest_book/index_off', 'Service.GuestBook/index_off');
    Route::get('/guest_book/look', 'Service.GuestBook/look');
    Route::get('/guest_book/reply', 'Service.GuestBook/reply');
    Route::get('/guest_book/send', 'Service.GuestBook/send');
    Route::get('/guest_book/reply_look', 'Service.GuestBook/reply_look');
    Route::post('/guest_book/send', 'Service.GuestBook/send');
    //FAQ管理
    Route::get('/Faq/index', 'Service.Faq/index');
    Route::get('/Faq/faq_recycle', 'Service.Faq/faq_recycle');
    Route::get('/Faq/add', 'Service.Faq/add');
    Route::get('/Faq/edit', 'Service.Faq/edit', [], ['id' => '\d+']);
    Route::post('/Faq/save', 'Service.Faq/save');
    Route::post('/faq/byStatus', 'Service.Faq/byStatus');
    Route::post('/faq/listorder', 'Service.Faq/listorder');

    //SN管理
    Route::get('/soft/index', 'Service.Soft/index');
    Route::get('/soft/add', 'Service.Soft/add');
    Route::get('/soft/edit', 'Service.Soft/edit', [], ['id' => '\d+']);
    Route::get('/Soft/add_model', 'Service.Soft/add_model');
    Route::get('/soft/saveID', 'Service.Soft/saveID');
    Route::get('/soft/edit_model', 'Service.Soft/edit_model', [], ['id' => '\d+']);
    Route::post('soft/edit', 'Service.Soft/edit', [], ['id' => '\d+']);
    Route::post('Soft/byStatus', 'Service.Soft/byStatus');
    //SN分类管理
    Route::get('/cate/index', 'Service.Cate/index');
    Route::get('/cate/add', 'Service.Cate/add');
    Route::post('/cate/index', 'Service.Cate/index');
    Route::post('/cate/byStatus', 'Service.Cate/byStatus');

    Route::get('/model/index', 'Service.Model/index');
    Route::get('/model/add', 'Service.Model/add');
    Route::get('/model/edit', 'Service.Model/edit');
    Route::get('/model/add_soft', 'Service.Model/add_soft');
    Route::get('/Model/saveID', 'Service.Model/saveID');
    Route::get('/Model/edit_soft', 'Service.Model/edit_soft', [], ['id' => '\d+']);
    Route::post('/Model/saveID', 'Service.Model/saveID');
    Route::post('/model/edit', 'Service.Model/edit');
    Route::post('/model/byStatus', 'Service.Model/byStatus');

    Route::get('/sn/index', 'Service.Sn/index');
    Route::get('/sn/add', 'Service.Sn/add');
    Route::get('/sn/edit', 'Service.Sn/edit', [], ['id' => '\d+']);
    Route::get('/old_sn/index', 'Service.OldSn/index');
    Route::get('/old_sn/add', 'Service.OldSn/add');
    Route::post('/old_sn/index', 'Service.OldSn/index');
    Route::post('/sn/index', 'Service.Sn/index');
    Route::post('/sn/byStatus', 'Service.Sn/byStatus');

    /**
     * 资源管理
     */
    Route::rule('/media/index', 'Media.Index/index')->name('media_index');
    //图片
    Route::rule('/media/image/lists', 'Media.Image/lists')->name('image_lists');
    Route::rule('/media/image/create/folder', 'Media.Image/createFolder')->name('create_image_folder');
    Route::rule('/media/image/upload', 'Media.Image/upload')->name('image_upload');
    Route::rule('/media/image/del', 'Media.Image/delImage')->name('del_image');
    //驱动
    Route::rule('/media/driver/lists', 'Media.Driver/lists')->name('driver_lists');
    Route::rule('/media/driver/upload', 'Media.Driver/upload')->name('upload_driver');
    Route::rule('/media/driver/create/folder', 'Media.Driver/createFolder')->name('create_driver_folder');
    Route::rule('/media/driver/del', 'Media.Driver/del')->name('del_driver');

    Route::rule('/media/videos/lists', 'Media.Videos/lists')->name('videos_lists');
    Route::rule('/media/videos/upload', 'Media.Videos/upload')->name('upload_videos');
    Route::rule('/media/videos/create/folder', 'Media.Videos/createFolder')->name('create_videos_folder');
    Route::rule('/media/videos/del', 'Media.Videos/del')->name('del_video');


    //搜索索引管理
    Route::get('/search/index', 'Search/index');
    Route::get('/search/createProduct', 'Search/createProduct');
    Route::get('/search/createDriver$', 'Search/createDriver');
    Route::get('/search/createIndex$', 'Search/createIndex');
    Route::get('/search/getProduct$', 'Search/searchProduct');
    ## 招聘管理
    Route::rule('/jobs/Category$', 'Jobs.Category/index')->name('jobs_category');
    Route::rule('/jobs/Category/add$', 'Jobs.Category/add')->name('add_jobs_category');
    Route::rule('/jobs/Category/edit/:id$', 'Jobs.Category/edit')->name('edit_jobs_category');
    Route::rule('/jobs/social$', 'Jobs.Social/index')->name('jobs_social');
    Route::rule('/jobs/social/sort/:id$', 'Jobs.Social/sort')->pattern(['id' => '\d+'])->name('sort_jobs');
    Route::rule('/jobs/social/add$', 'Jobs.Social/add')->name('add_social_job');
    Route::rule('/jobs/social/edit/:id$', 'Jobs.Social/edit')->name('edit_social_job');
    Route::rule('/jobs/social/resume/list$', 'Jobs.SocialResume/index')->name('social_resume_list');
    Route::rule('/jobs/social/resume/views$', 'Jobs.SocialResume/views')->name('view_social_resume');
    Route::rule('/jobs/social/resume/readed/:id$', 'Jobs.SocialResume/readed')->name('read_social_resume');
    Route::get('/$', 'Index/index');
})->prefix('wavlink/');