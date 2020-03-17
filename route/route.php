<?php
use think\facade\Route;
Route::group('customer', function () {
    Route::get('/login$', 'Login/index');
    Route::miss('en_us/Error/index');
})->prefix('customer/');

Route::get('/login$','customer/login/index');
Route::get('/register$','customer/login/index');

