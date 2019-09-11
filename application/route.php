<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// 所有的路由都是有用的，不要随意修改，有些路由是为了缩短印刷网址而建立的
// +----------------------------------------------------------------------

use think\Route;


Route::rule([
    'zh_cn/base/ie'=>'zh_cn/base/ie',
    'en_us/base/ie'=>'en_us/base/ie',
]);
/**
 * 用户后台路由
 * 如果在控制器是用url生成路由，用后面的
 */
Route::rule([
    //登录页面
    '/login'=>'customer/Login/index',
    'customer/login' => 'customer/Login/index',
    //用户后台首页
    'customer/index' => 'customer/Index/index',
    //用户注销页面提
    'customer/logout'=>'customer/logout/index',
    //信息修改提交接口
    'customer/info' =>'customer/info/info',
    'customer/edit/password'=>'customer/info/editPassword',
    //找回密码接口
    'customer/forgotten'=>'customer/forgotten/forgotten',
    //执行重置密码
    'customer/reset/:email'=>'customer/forgotten/reset',
    //用户注册页面
    'customer/register' => 'customer/Register/index',
    '/register'=>'customer/Register/index',
    //质保
    'customer/warranty'=>'customer/warranty/index',
    'customer/warranty/register'=>'customer/warranty/register',
    'customer/warranty/extend/:id'=>'customer/warranty/extend',
    'customer/warranty/apply/:id'=>'customer/warranty/apply',
    //SN验证并返回数据
    'customer/sn/Verification' =>'customer/sn/Verification',
    //ticket
    'customer/ticket/:user_id/:sn'=>'customer/ticket/add',
    'customer/ticket/save'=>'customer/ticket/save',
    'customer/ticket'=>'customer/ticket/index',
    'customer/ticket/add'=>'customer/ticket/addTicket',
],'','GET|POST');

/***
 * 网站默认是英文模块
 * 英文路由,第一个参数是路由地址,第二个参数是模块/控制器/方法。
 * 规则是以数组形式
 */
//首页URL
Route::rule([
    //英文网站首页
    '/'=>'index/AutomaticJump/index',
    'en_us/'=>'en_us/Index/index',
    'en_us/index'=>'en_us/Index/index',
    //产品详情页
    'en_us/product/:product'=>'en_us/Product/details',
    //产品分类列表页
    'en_us/category/:category'=>'en_us/Category/index',
    //子分类产品列表页
    'en_us/product/:category'=>'en_us/Category/index',
    //驱动列表页面
    'en_us/drivers/'=>'en_us/Drivers/index',
    //说明书，手册
    'en_us/manual/'=>'en_us/Manual/index',
    '/manual'=>'en_us/Manual/index',
    'en_us/manual/:category'=>'en_us/Manual/category',
    'en_us/manual/details/:url_title'=>'en_us/Manual/details',
    // 驱动详情页
    'en_us/drivers/details/:drivers'=>'en_us/Drivers/details',
    //子分类驱动列表页
    'en_us/drivers/:category'=>'en_us/Drivers/category',
    //视频列表页面
    'en_us/video/:category'=>'en_us/Video/index',
    //视频详情页
    'en_us/video/detail/:video'=>'en_us/Video/detail',
    //    视频子列表页
//    'en_us/video/:category'=>'en_us/Video/category',
    //关于我们
    'en_us/about/:about'=>'en_us/About/index',
    //固件下载
    'en_us/guest_book/'=>'en_us/GuestBook/add',
    'en_us/guest_book/:sn'=>'en_us/GuestBook/detail',
    //产品搜索路由
    'en_us/search/product'=>'en_us/Search/product',
    //驱动搜索
    'en_us/search/drivers'=>'en_us/Search/drivers',
    //事件列表页面
    'en_us/article/:url_title' =>'en_us/Article/index',
    //事件详情页
    'en_us/article/details/:article'=>'en_us/Article/details',
    //文档列表页
    'en_us/document/:url_title'=>'en_us/Document/index',
    //文档详情页
    'en_us/document/details/:document' => 'en_us/Document/details',
    //    //FAQ列表页面
    'en_us/faq/'  => 'en_us/Faq/index',
    'en/faq/'=>'en_us/Faq/index',
    //分类faq下的faq集锦
    'en_us/faq/:url_title' => 'en_us/Faq/category',
    'en/faq/:url_title' => 'en_us/Faq/category',
    //faq详情页
    'en_us/faq/details/:url_title'=>'en_us/Faq/details',
    'en/faq/details/:url_title'=>'en_us/Faq/details',
    'en_us/marketing/details'=>'en_us/Marketing/details',
    'en_us/marketing/details/:name'=>'en_us/Marketing/details',
    'en_us/marketing/index'=>'en_us/Marketing/index',
    'en_us/sn/verification'=>'en_us/sn/index',
],'','GET');


/**
 * 中文网站路由规则，写法和英文网站一样，
 */
Route::rule([
    //中文首页
    'zh_cn/index'=>'zh_cn/Index/index',
    //产品详情页
    'zh_cn/product/:product'=>'zh_cn/Product/details',
     //产品分类列表页
    'zh_cn/category/:category'=>'zh_cn/Category/index',
    //子分类产品列表页
    'zh_cn/product/:category'=>'zh_cn/Category/index',
    //驱动列表页面
    'zh_cn/drivers/'=>'zh_cn/Drivers/index',
    // 驱动详情页
    'zh_cn/drivers/details/:drivers'=>'zh_cn/Drivers/details',
    //子分类驱动列表页
    'zh_cn/drivers/:category'=>'zh_cn/Drivers/category',
    //视频列表页面
    'zh_cn/video/:category'=>'zh_cn/Video/index',
    //视频详情页
    'zh_cn/video/detail/:video'=>'zh_cn/Video/detail',
//    //    视频子列表页
//    'zh_cn/video/:category'=>'zh_cn/Video/category',
    //关于我们
    'zh_cn/about/:about'=>'zh_cn/About/index',
    //固件下载
    'zh_cn/guest_book/'=>'zh_cn/guest_book/add',
    'zh_cn/guest_book/:sn'=>'zh_cn/guest_book/detail',
    //产品搜索路由
    'zh_cn/search/product'=>'zh_cn/Search/product',
    //驱动搜索
    'zh_cn/search/drivers'=>'zh_cn/Search/drivers',
    //事件列表页面
    'zh_cn/article/:url_title' =>'zh_cn/Article/index',
    //事件详情页
    'zh_cn/article/details/:article'=>'zh_cn/Article/details',
    //文档列表页
    'zh_cn/document/:url_title'=>'zh_cn/Document/index',
    //文档详情页
    'zh_cn/document/details/:document' => 'zh_cn/Document/details',
//    //FAQ列表页面
    'zh_cn/faq/'  => 'zh_cn/Faq/index',
    'cn/faq/'  => 'zh_cn/Faq/index',
    //分类faq下的faq集锦
    'zh_cn/faq/:url_title' => 'zh_cn/Faq/category',
    'cn/faq/:url_title' => 'zh_cn/Faq/category',
    //faq详情页
    'zh_cn/faq/details/:url_title'=>'zh_cn/Faq/details',
    'cn/faq/details/:url_title'=>'zh_cn/Faq/details',
    'zh_cn/marketing/details'=>'zh_cn/Marketing/details',
    'zh_cn/marketing/details/:name'=>'zh_cn/Marketing/details',
],'','GET');
//UK 路由表
Route::rule([
    'uk/guest_book/'=>'uk/GuestBook/add',
    'uk/guest_book/:sn'=>'uk/GuestBook/detail',
    'uk/about/:about'=>'uk/About/index',
]);
//winstars 数据采集 用完即焚
Route::post('api/winstars','api/winstars/add');
Route::get('api/index/test','api/index/index');
