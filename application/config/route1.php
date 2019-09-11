<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/9/11
 * Time: 10:16
 */

use think\Route;

Route::group('wavlink', function () {
    Route::get('/index', 'wavlink/index/index');
    Route::get('/login/index', 'wavlink/login/index');
    Route::post('/login/index', 'wavlink/login/index');
    Route::get('/login', 'wavlink/login/index');
    Route::get('/content/index','wavlink/content/index');
});
