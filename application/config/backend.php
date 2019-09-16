<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/9/11
 * Time: 10:16
 */

use think\Route;

//get请求组
Route::group('wavlink', function () {
    //登录登出
    Route::get('/login/index', 'wavlink/login/index');
    Route::get('/login/logout', 'wavlink/login/logout');
    Route::get('/login', 'wavlink/login/index');
    /***
     * 内容管理模块路由
     */
    //几个首页
    Route::get('/index', 'wavlink/index/index');
    Route::get('/content/index', 'wavlink/content/index');
    //分类url
    Route::get('/category/index', 'wavlink/Category/index');
    Route::get('/category/add', 'wavlink/Category/add');
    Route::get('/Category/byStatus', 'wavlink/Category/byStatus');
    Route::get('/Category/edit', 'wavlink/Category/edit', [], ['id' => '\d+']);
    //推荐位
    Route::get('/Featured/index', 'wavlink/Featured/index');
    Route::get('/Featured/byStatus', 'wavlink/Featured/byStatus');
    Route::get('/Featured/add', 'wavlink/Featured/add');
    Route::get('/Featured/edit', 'wavlink/Featured/edit', [], ['id' => '\d+']);
    //首页推荐产品
    Route::get('/images/index', 'wavlink/images/index');
    Route::get('/images/add', 'wavlink/images/add');
    Route::get('/images/edit', 'wavlink/images/edit', [], ['id' => '\d+']);
    Route::get('/images/images_recycle', 'wavlink/images/images_recycle');
    //产品管理
    Route::get('/product/index', 'wavlink/product/index');
    Route::get('/product/add', 'wavlink/product/add');
    Route::get('/product/product_edit', 'wavlink/product/product_edit', [], ['id' => '\d+']);
    Route::get('/product/product_recycle', 'wavlink/product/product_recycle');
    //营销管理
    Route::get('/marketing/index', 'wavlink/marketing/index');
    Route::get('/marketing/add', 'wavlink/Marketing/add');
    Route::get('/marketing/edit', 'wavlink/Marketing/edit', [], ['id' => '\d+']);
    /***
     * 系统管理模块路由
     *
     */
    Route::get('/system/index', 'wavlink/System/index');
    //语言
    Route::get('/language/index', 'wavlink/Language/index');
    Route::get('/language/add', 'wavlink/Language/add');
    Route::get('/language/edit', 'wavlink/Language/edit', [], ['id' => '\d+']);
    Route::get('/language/language_stop', 'wavlink/Language/language_stop');
    //关于我们
    Route::get('/About/index', 'wavlink/About/index');
    Route::get('/About/add', 'wavlink/About/add');
    Route::get('/About/edit', 'wavlink/About/edit', [], ['id' => '\d+']);
    //站点配置
    Route::get('setting/index', 'wavlink/Setting/index');
    //管理员列表
    Route::get('manger/index', 'wavlink/manger/index');
    Route::get('manger/add', 'wavlink/manger/add');
    Route::get('manger/edit', 'wavlink/manger/edit', [], ['id' => '\d+']);
    Route::get('manger/password', 'wavlink/manger/password', [], ['id' => '\d+']);
    //禁用的管理员
    Route::get('manger/manger_stop','wavlink/manger/manger_stop');

    //权限组
    Route::get('auth_group/index', 'wavlink/AuthGroup/index');
    Route::get('auth_group/add', 'wavlink/AuthGroup/add');
    Route::get('auth_group/edit', 'wavlink/AuthGroup/edit', [], ['id' => '\d+']);
    //权限
    Route::get('auth_rule/index', 'wavlink/AuthRule/index');
    Route::get('auth_rule/add', 'wavlink/AuthRule/add');
    Route::get('auth_rule/edit', 'wavlink/AuthRule/edit', [], ['id' => '\d+']);

    //服务模块
    Route::get('service/index','wavlink/service/index');


});

//post请求组
Route::group('wavlink', function () {
    /***
     * 登录请求post接口路由
     */
    Route::post('/login/index', 'wavlink/login/index');
    /***
     * 内容模块POST请求接口
     */
    Route::post('/category/save', 'wavlink/Category/save');
    Route::post('/Featured/save', 'wavlink/Featured/save');
    Route::post('/images/save', 'wavlink/Images/save');
    Route::post('/product/save', 'wavlink/product/save');
    Route::post('/marketing/save', 'wavlink/Marketing/save');
    Route::post('/Category/byStatus', 'wavlink/Category/byStatus');
    Route::post('/Featured/byStatus', 'wavlink/Featured/byStatus');
    Route::post('/Images/byStatus', 'wavlink/Images/byStatus');
    Route::post('/product/byStatus', 'wavlink/product/byStatus');
    Route::post('/marketing/byStatus', 'wavlink/Marketing/byStatus');
    Route::post('/Images/listorder', 'wavlink/Images/listorder');
    Route::post('/product/listorder', 'wavlink/product/listorder');
    Route::post('/product/sort', 'wavlink/product/sort');
    Route::post('/product/mark', 'wavlink/product/mark');
    /**
     * 系统管理模块Post请求路由
     */
    Route::post('/language/save', 'wavlink/Language/save');
    Route::post('/About/save', 'wavlink/About/save');
    Route::post('/Setting/save', 'wavlink/Setting/save');
    Route::post('/manger/save', 'wavlink/manger/save');
    Route::post('/auth_group/save', 'wavlink/AuthGroup/save');
    Route::post('/auth_rule/save', 'wavlink/AuthRule/save');
    Route::post('/language/byStatus', 'wavlink/Language/byStatus');
    Route::post('/About/byStatus', 'wavlink/About/byStatus');
    Route::post('/manger/byStatus', 'wavlink/manger/byStatus');
    Route::post('/auth_group/byStatus', 'wavlink/AuthGroup/byStatus');
    Route::post('/auth_rule/byStatus', 'wavlink/AuthRule/byStatus');
});
