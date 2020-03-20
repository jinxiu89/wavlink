<?php
use think\facade\Route;
Route::group('customer', function () {
    Route::rule('/register$', 'Register/index','GET|POST')->name('customer_register');
    Route::rule('/login$', 'Login/index','GET|POST')->name('customer_login');
    Route::get('/logout$', 'Logout/index');
    Route::rule('/info$','Info/info','GET|POST')->name('customer_info');
    Route::get('/warranty$','Warranty/index')->name('customer_warranty_list');
    Route::get('/warranty/register$','Warranty/register')->name('customer_warranty_register');
    Route::post('/edit/password$','Info/editPassword');
    Route::miss('en_us/Error/index');
})->prefix('customer/');

Route::rule('/login$','customer/login/index','GET|POST');
Route::rule('/register$','customer/login/index','GET|POST');

