<?php

/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/1/5
 * Time: 10:06
 */
/**
 * 邮件服务相关配置
 */
return array(
    'charset' => 'utf-8',                  // 邮件编码
    'smtp_debug' => 4,                     // Debug模式。0: 关闭，1: 客户端消息，2: 客户端和服务器消息，3: 2和连接状态，4: 更详细
    'debug_output' => 'html',              // Debug输出类型。`echo`（默认）,`html`,或`error_log`
    'host' => 'smtpdm-ap-southeast-1.aliyun.com',              // SMPT服务器地址
    'port' => 465,                         // 端口号。默认25
    'smtp_auth' => true,                   // 启用SMTP认证
    'smtp_secure' => 'ssl',                // 启用安全协议。''（默认）,'ssl'或'tls'，留空不启用
    'username' => 'system@service.wavlink.us',  // SMTP登录邮箱
    'password' => 'Wh32Ym69B10c',          // SMTP登录密码。126邮箱使用客户端授权码，QQ邮箱用独立密码
    'from' => 'system@service.wavlink.us',          // 发件人邮箱
    'from_name' => 'system',                 // 发件人名称
    'reply_to' => 'content@wavlink.com',                      // 回复邮箱的地址。留空取发件人邮箱
    'reply_to_name' => 'Wavlink.com',
);