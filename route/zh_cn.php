<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/9/11
 * Time: 10:16
 */

use think\facade\Route;

Route::group('zh_cn', function () {
    Route::get('/index', 'Index/index');
    Route::get('/category/:category', 'Category/category')->pattern(['category' => '[\w-]+']);
    Route::get('/product/:product$', 'Product/details')->pattern(['product' => '[\w-]+']);
    Route::get('/about/:about', 'About/index')->pattern(['about' => '[\w-]+']);
    Route::get('/drivers/download/:detail$', 'Drivers/detail')->pattern(['detail' => '[\w-]+']);
    Route::get('/drivers/:category$', 'Drivers/category');
    Route::get('/drivers$', 'Drivers/index');
    Route::get('/manuals', 'Manual/index');
    Route::get('/manuals/index', 'Manual/index');
    Route::get('/firmware/details/:title', 'Firmware/details')->pattern(['title' => '[\w-]+']);
    Route::get('/firmware', 'Firmware/index');
    Route::get('/faq/details/:url_title', 'Faq/details')->pattern(['url_title' => '[\w-]+']);
    Route::get('/faq/:url_title', 'Faq/category')->pattern(['url_title' => '[\w-]+']);
    Route::get('/faq', 'Faq/index');
    Route::get('/guest_book/', 'GuestBook/add');
    Route::post('/guest_book/save', 'GuestBook/save');
    Route::get('/article/index', 'Article/index');
    Route::get('/article/details/:url_title', 'Article/details')->pattern(['url_title' => '[\w-]+']);
    Route::get('/article', 'Article/index');
    Route::get('/video/:url_title', 'Video/details')->pattern(['url_title' => '[\w-]+']);
    Route::get('/video', 'Video/index');
    Route::get('/search', 'Search/results')->pattern(['key' => '[\w-]+', 'type' => '[\w-]+', 'page' => '[\d]+']);
    //招聘专项
    Route::get('/jobs/social$', 'Social/index');
    Route::get('/jobs/social/list$', 'Social/list');
    Route::get('/jobs/social/details/:url_title$', 'Social/details')->name('social_details');
    Route::get('/jobs/social/gain/:url_title$', 'Social/gain')->name('gain_social_job');
    //包装印刷指定的url地址 model=>'该产品的标准型号'
    Route::get('/:model$', 'Support/model', [], ['model' => '[\w-]+'])->name('support_bz');
    Route::get('/tuya/Appdownload', 'Tuya/index');
    //根路由在最下面
})->prefix('en_us/');