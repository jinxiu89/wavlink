<?php
use think\facade\Route;
Route::group('customer', function () {
    Route::get('/register$', 'Register/index');
    Route::post('/register$', 'Register/index');
    Route::get('/login$', 'Login/index');
    Route::post('/login$', 'Login/index');
    Route::get('/logout$', 'Logout/index');
    Route::get('/info$','Info/info');
    Route::post('/info$','Info/info');
    Route::get('/warranty$','Warranty/index');
    Route::get('/warranty/register$','Warranty/register');
    Route::post('/edit/password$','Info/editPassword');
    Route::miss('en_us/Error/index');
})->prefix('customer/');

Route::get('/login$','customer/login/index');
Route::post('/login$','customer/login/index');
Route::get('/register$','customer/login/index');

