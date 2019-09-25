<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/9/11
 * Time: 10:16
 */

use think\Route;

Route::group('zh_cn', function () {
    Route::get('/index', 'Index/index');
    Route::get('/category/:category','Category/index');
    Route::get('/drivers','Drivers/index');
    Route::get('/drivers/:category','Drivers/category');
    Route::get('/manuals','Manual/index');
    Route::get('/manuals/index','Manual/index');
    //根路由在最下面
    Route::get('/', 'Index/index');
});

//Route::group('zh_cn',function (){
//    Route::get('/index','Index/index');
//});

//
///**
// * 中文网站路由规则，写法和英文网站一样，
// */
//Route::rule([
//    //中文首页
//    'zh_cn/index' => 'zh_cn/Index/index',
//    //产品详情页
//    'zh_cn/product/:product' => 'zh_cn/Product/details',
//    //产品分类列表页
//    'zh_cn/category/:category' => 'zh_cn/Category/index',
//    //子分类产品列表页
//    'zh_cn/product/:category' => 'zh_cn/Category/index',
//    //驱动列表页面
//    'zh_cn/drivers/' => 'zh_cn/Drivers/index',
//    // 驱动详情页
//    'zh_cn/drivers/details/:drivers' => 'zh_cn/Drivers/details',
//    //子分类驱动列表页
//    'zh_cn/drivers/:category' => 'zh_cn/Drivers/category',
//    //视频列表页面
//    'zh_cn/video/:category' => 'zh_cn/Video/index',
//    //视频详情页
//    'zh_cn/video/detail/:video' => 'zh_cn/Video/detail',
////    //    视频子列表页
////    'zh_cn/video/:category'=>'zh_cn/Video/category',
//    //关于我们
//    'zh_cn/about/:about' => 'zh_cn/About/index',
//    //固件下载
//    'zh_cn/guest_book/' => 'zh_cn/guest_book/add',
//    'zh_cn/guest_book/:sn' => 'zh_cn/guest_book/detail',
//    //产品搜索路由
//    'zh_cn/search/product' => 'zh_cn/Search/product',
//    //驱动搜索
//    'zh_cn/search/drivers' => 'zh_cn/Search/drivers',
//    //事件列表页面
//    'zh_cn/article/:url_title' => 'zh_cn/Article/index',
//    //事件详情页
//    'zh_cn/article/details/:article' => 'zh_cn/Article/details',
//    //文档列表页
//    'zh_cn/document/:url_title' => 'zh_cn/Document/index',
//    //文档详情页
//    'zh_cn/document/details/:document' => 'zh_cn/Document/details',
////    //FAQ列表页面
//    'zh_cn/faq/' => 'zh_cn/Faq/index',
//    'cn/faq/' => 'zh_cn/Faq/index',
//    //分类faq下的faq集锦
//    'zh_cn/faq/:url_title' => 'zh_cn/Faq/category',
//    'cn/faq/:url_title' => 'zh_cn/Faq/category',
//    //faq详情页
//    'zh_cn/faq/details/:url_title' => 'zh_cn/Faq/details',
//    'cn/faq/details/:url_title' => 'zh_cn/Faq/details',
//    'zh_cn/marketing/details' => 'zh_cn/Marketing/details',
//    'zh_cn/marketing/details/:name' => 'zh_cn/Marketing/details',
//], '', 'GET');
////UK 路由表
//Route::rule([
//    'uk/guest_book/' => 'uk/GuestBook/add',
//    'uk/guest_book/:sn' => 'uk/GuestBook/detail',
//    'uk/about/:about' => 'uk/About/index',
//]);
////winstars 数据采集 用完即焚
//Route::post('api/winstars', 'api/winstars/add');
//Route::get('api/index/test', 'api/index/index');