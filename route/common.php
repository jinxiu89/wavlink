<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/4/16 10:00
 * @User: admin
 * @Current File : common.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

use think\facade\Route;


/***
 * 前端路由控制
 * 对于根来说，他需要的是得到语言信息 然后跳转
 * 这些路径不论天荒地老，岁月更替，都需要保留
 */
// 驱动下载永久短地址
Route::get('/driver$', 'en_us/Common/driver')->name('driver');
// 团队协议
Route::get('/en_us/terms$', 'en_us/Common/terms')->name('terms'); //
Route::get('/en_us/privacy$', 'en_us/Common/privacy')->name('privacy');
//说明书下载永久短网址
Route::get('/manual$', 'en_us/Common/manual')->name('manual');
//说明书短网址
Route::get('/QSG$', 'en_us/Common/manual')->name('qsg');
//语言切换功能更
Route::get('/language/:code', 'en_us/Language/setLanguage', [], ['code' => '[\w-]+']);
//手动错误
Route::get('/notfound', 'en_us/Base/notFound')->name('404');
Route::get('/server_error', 'en_us/Base/serverError')->name('500');
//自动跳转路由
Route::get('/', 'en_us/Base/autoload');
//访问路由不存在时触发miss路由
Route::miss('en_us/Common/miss');  //当所有的路由都匹配不到的时候 就会走到这个miss路由上来
//原登录路由，
Route::rule('/login$', 'customer/User/login', 'GET|POST')->parent(['next' => '[\w-]+']);
//原注册路径，永久保留
Route::rule('/register$', 'customer/User/register', 'GET|POST');
//发送验证码连接，永久保留
Route::rule('/verify/code$', 'en_us/Common/verify', 'GET')->name('gen_verify');
Route::rule('/list/obj$', 'customer/Base/listObj', 'GET')->name('listObj');
Route::get('/index$','en_us/Index/index');
