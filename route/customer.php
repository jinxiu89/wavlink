<?php
use think\facade\Route;
Route::group('customer', function () {
    //注册
    Route::rule('/register$', 'User/register','GET|POST')->name('customer_register');
    //验证码
    Route::rule('/verification$', 'Base/sendVerification','GET')
        ->parent(['email'=>'[\w!#$%&\'*+/=?^_`{|}~-]+(?:\.[\w!#$%&\'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?','phone'=>'\d+'])
        ->name('verification');
    Route::post('/send_code','Base/sendCode')->name('send_code');
    //登入登出
    Route::rule('/login$', 'User/login','GET|POST')->name('customer_login')->parent(['next'=>'[\w-]+']);
    Route::get('/logout$', 'User/logout')->name('customer_logout');
    //找回密码
    Route::rule('/forgot/password$','User/forgotPassword','GET|POST')->name('forgot_password');
    Route::rule('/change/password$','User/changePassword','GET|POST')->name('change_password');
    //登录情况下重设密码
    Route::rule('/reset/password','User/resetPassword','GET|POST')->name('reset_password');
    //用户信息
    Route::rule('/info$','User/info','GET|POST')->name('customer_info');
    //修改名字
    Route::rule('/changeName','User/changeName')->name('changeName')->parent(['id'=>'[-\d+]']);
    //修改性别
    Route::rule('/changeGender','User/changeGender','GET|POST')->name('changeGender')->parent(['id'=>'[-\d+]']);
    //修改生日
    Route::rule('/changeBirthday','User/changeBirthday','GET|POST')->name('changeBirthday')->parent(['id'=>'[-\d+]']);
    //改手机号
    Route::rule('/changePhone','User/changePhone','GET|POST')->name('changePhone')->parent(['id'=>'[-\d+]']);
    //修改邮箱
    Route::rule('/changeEmail','User/changeEmail','GET|POST')->name('changeEmail')->parent(['id'=>'[-\d+]']);

    //修改国家
    Route::rule('/changeCountry','User/changeCountry','GET|POST')->name('changeCountry')->parent(['id'=>'[-\d+]']);
    //修改邮政编码
    Route::rule('/changeCode','User/changeCode','GET|POST')->name('changeCode')->parent(['id'=>'[-\d+]']);
    //修改账单地址
    Route::rule('/changeBillingAddress','User/changeBillingAddress','GET|POST')->name('changeBillingAddress')->parent(['id'=>'[-\d+]']);
    //修改收货地址
    Route::rule('/changeDeliveryAddress','User/changeDeliveryAddress','GET|POST')->name('changeDeliveryAddress')->parent(['id'=>'[-\d+]']);

    Route::post('/edit/password$','Info/editPassword')->name('customer_password');
    //产品注册
    Route::rule('/product/register$','Product/register','GET|POST')->parent(['user_id'=>'[\d+]'])->name('customer_product_register');
    Route::rule('/product/add$','Product/addProduct','GET|POST')->name('customerAddProduct');
    Route::get('/product/lists$','Product/lists')->name('customer_product_list');
    Route::post('/product/del','Product/delete')->name('customer_del_product');
    Route::get('/testEmail','User/testEmail');

    Route::rule('/index$','Index/index','GET')->name('customer_index');
})->prefix('customer/');
