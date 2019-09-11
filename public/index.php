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

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
//定义静态文件目录
define('EN_HTML_PATH',__DIR__.'/../public/build_html/en_us/view/');
//定义日志目录
define('LOG_PATH', __DIR__ . '/../log/');
//定义extra 配置目录
define('EXTRA_PATH',__DIR__.'/../application/extra');
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';


