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
    Route::get('/manuals', 'Manual/index')->name('manual_list');
    Route::get('/faq/details/:url_title', 'Faq/details')->pattern(['url_title' => '[\w-]+']);
    Route::get('/faq/:url_title', 'Faq/category')->pattern(['url_title' => '[\w-]+']);
    Route::get('/faq', 'Faq/index');
    Route::post('/guest_book/save', 'GuestBook/save');
    Route::get('/guest_book/', 'GuestBook/add');
    Route::get('/article/index', 'Article/index');
    Route::get('/article/details/:url_title', 'Article/details')->pattern(['url_title'=>'[\w-]+']);
    Route::get('/article', 'Article/index');
    Route::get('/tuya/Appdownload', 'Tuya/index');
    Route::get('/video/category/:url_title', 'Video/category')->pattern(['url_title' => '[\w-]+']);
    Route::get('/video/:url_title', 'Video/details')->pattern(['url_title' => '[\w-]+']);
    Route::get('/video', 'Video/index');
    Route::get('/search','Search/results')->pattern(['key'=>'[\w-]+','type'=>'[\w-]+','page'=>'[\d]+']);
    Route::get('/tuya/Appdownload','Tuya/index');
    Route::get('/', 'Index/index');
})->prefix('en_us/');