<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/11/28 15:53
 * @User: kevin
 * @Current File : api.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/
use think\facade\Route;
Route::group('api',function (){
    Route::group('v1',function (){
        Route::get('/category$','v1.category/index');
    });
})->prefix('api/');

//资源路由 居然不支持目录分级
/*Route::group('api',function (){
    Route::group('v1',function (){
        Route::resource('category','api/v1.Category/index');
    });
});*/