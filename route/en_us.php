<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/9/11
 * Time: 10:16
 */

use think\facade\Route;

Route::group('en_us', function () {
    Route::get('/index', 'Index/index');
    Route::get('/category/:category', 'Category/index')->pattern(['category' => '[\w-]+']);
    Route::get('/product/:product', 'Product/details')->pattern(['product' => '[\w-]+']);
    Route::get('/about/:about', 'About/index')->pattern(['about' => '[\w-]+']);
    Route::get('/drivers/:category', 'Drivers/category')->pattern(['category' => '[\w-]+']);
    Route::get('/drivers', 'Drivers/index');
    Route::get('/firmware/details/:title', 'Firmware/details')->pattern(['title' => '[\w-]+']);
    Route::get('/firmware', 'Firmware/index');
    Route::get('/manuals/index', 'Manual/index');
    Route::get('/manuals/:category', 'Manual/category')->pattern(['category' => '[\w-]+']);
    Route::get('/manuals', 'Manual/index');
    Route::get('/faq/details/:url_title', 'Faq/details')->pattern(['url_title' => '[\w-]+']);
    Route::get('/faq/:url_title', 'Faq/category')->pattern(['url_title' => '[\w-]+']);
    Route::get('/faq', 'Faq/index');
    Route::post('/guest_book/save', 'GuestBook/save');
    Route::get('/guest_book/', 'GuestBook/add');
    Route::get('/article/index', 'Article/index');
    Route::get('/article/details/:url_title', 'Article/details')->pattern(['url_title'=>'[\w-]+']);
    Route::get('/article', 'Article/index');
    Route::get('/tuya/Appdownload', 'Tuya/index');
    Route::get('/video/:url_title', 'Video/details')->pattern(['url_title' => '[\w-]+']);
    Route::get('/video', 'Video/index');
    Route::get('/search','Search/results')->pattern(['key'=>'[\w-]+','type'=>'[\w-]+','page'=>'[\d]+']);
    Route::get('/tuya/Appdownload','Tuya/index');
    Route::get('/', 'Index/index');
});


/***
 * 前端路由控制
 * 对于根来说，他需要的是得到语言信息 然后跳转
 *
 */
Route::get('/', 'en_us/Base/autoload');
Route::get('/language/:code', 'en_us/Base/setLanguage', [], ['code' => '[\w-]+']);