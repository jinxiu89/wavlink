<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

Route::group('en_us', function () {
    Route::get('/index', 'Index/index');
}, ['ext' => 'html']);

Route::group('zh_cn',function (){
    Route::get('/index','Index/index');
});

//return [
//    /***
//     * 网站默认是英文模块
//     * 英文路由,第一个参数是路由地址,第二个参数是模块/控制器/方法。
//     * 规则是以数组形式
//     */
////首页URL
//    Route::rule([
//        //英文网站首页
//        '/' => 'index/AutomaticJump/index',
////        'en_us/' => 'en_us/Index/index',
////        'en_us/index' => 'en_us/Index/index',
//        //产品详情页
//        'en_us/product/:product' => 'en_us/Product/details',
//        //产品分类列表页
//        'en_us/category/:category' => 'en_us/Category/index',
//        //子分类产品列表页
//        'en_us/product/:category' => 'en_us/Category/index',
//        //驱动列表页面
//        'en_us/drivers/' => 'en_us/Drivers/index',
//        //说明书，手册
//        'en_us/manual/' => 'en_us/Manual/index',
//        '/manual' => 'en_us/Manual/index',
//        'en_us/manual/:category' => 'en_us/Manual/category',
//        'en_us/manual/details/:url_title' => 'en_us/Manual/details',
//        // 驱动详情页
//        'en_us/drivers/details/:drivers' => 'en_us/Drivers/details',
//        //子分类驱动列表页
//        'en_us/drivers/:category' => 'en_us/Drivers/category',
//        //视频列表页面
//        'en_us/video/:category' => 'en_us/Video/index',
//        //视频详情页
//        'en_us/video/detail/:video' => 'en_us/Video/detail',
//        //    视频子列表页
////    'en_us/video/:category'=>'en_us/Video/category',
//        //关于我们
//        'en_us/about/:about' => 'en_us/About/index',
//        //固件下载
//        'en_us/guest_book/' => 'en_us/GuestBook/add',
//        'en_us/guest_book/:sn' => 'en_us/GuestBook/detail',
//        //产品搜索路由
//        'en_us/search/product' => 'en_us/Search/product',
//        //驱动搜索
//        'en_us/search/drivers' => 'en_us/Search/drivers',
//        //事件列表页面
//        'en_us/article/:url_title' => 'en_us/Article/index',
//        //事件详情页
//        'en_us/article/details/:article' => 'en_us/Article/details',
//        //文档列表页
//        'en_us/document/:url_title' => 'en_us/Document/index',
//        //文档详情页
//        'en_us/document/details/:document' => 'en_us/Document/details',
//        //    //FAQ列表页面
//        'en_us/faq/' => 'en_us/Faq/index',
//        'en/faq/' => 'en_us/Faq/index',
//        //分类faq下的faq集锦
//        'en_us/faq/:url_title' => 'en_us/Faq/category',
//        'en/faq/:url_title' => 'en_us/Faq/category',
//        //faq详情页
//        'en_us/faq/details/:url_title' => 'en_us/Faq/details',
//        'en/faq/details/:url_title' => 'en_us/Faq/details',
//        'en_us/marketing/details' => 'en_us/Marketing/details',
//        'en_us/marketing/details/:name' => 'en_us/Marketing/details',
//        'en_us/marketing/index' => 'en_us/Marketing/index',
//        'en_us/sn/verification' => 'en_us/sn/index',
//    ], '', 'GET')
//];
