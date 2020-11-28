<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
//header('X-Frame-Options: deny');
// [ 应用入口文件 ]
namespace think;
//加载基础文件
require __DIR__ . '/../thinkphp/base.php';

//定义vendor目录
define('VENDOR_PATH', __DIR__ . '/../vendor');
// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
define('PUBLIC_PATH',__DIR__);
// 定义
define('RUNTIME_PATH',__DIR__.'/../runtime/');
//定义日志目录
define('LOG_PATH', __DIR__ . '/../log/');
//定义extra 配置目录
define('EXTRA_PATH', __DIR__ . '/../application/extra');
// 加载框架引导文件
Container::get('app')->run()->send();


