<?php
use think\facade\Env;
return [
    // 错误显示信息,非调试模式有效
    'error_message' => 'Error pls wait a moment～',
    // 显示错误信息
    'show_error_msg' => false,
    'exception_handle' => '',
    'http_exception_template'    =>  [
        // 定义404错误的重定向页面地址
        404 =>  Env::get('app_path').'/error/404.html',
        // 还可以定义其它的HTTP status
        401 =>  Env::get('app_path').'/error/401.html',
        500 => Env::get('app_path').'/error/500.html'
    ],
];
