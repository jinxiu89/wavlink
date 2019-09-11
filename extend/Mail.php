<?php

/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/1/5
 * Time: 10:00
 */
use phpmailer\PHPMailer;
class Mail extends PHPMailer
{
    function __construct() {
        date_default_timezone_set('PRC');
        $this->CharSet = config('mail.charset');
        $this->isSMTP();
        $this->SMTPDebug = config('mail.smtp_debug');
        $this->Debugoutput = config('mail.debug_output');
        $this->Host = config('mail.host');                         // SMTP服务器地址
        $this->Port = config('mail.port');                         // 端口号
        $this->SMTPAuth = config('mail.smtp_auth');                // SMTP登录认证
        $this->SMTPSecure = config('mail.smtp_secure');            // SMTP安全协议
        $this->Username = config('mail.username');                 // SMTP登录邮箱
        $this->Password = config('mail.password');                 // SMTP登录密码
        $this->setFrom(config('mail.from'), config('mail.from_name'));            // 发件人邮箱和名称
        $this->addReplyTo(config('mail.reply_to'), config('mail.reply_to_name')); // 回复邮箱和名称
    }

    /**
     * 发送邮件
     * @param $toMail
     * @param $toName
     * @param $subject
     * @param $content
     * @param  [type] $toMail      收件人地址
     * @return bool|string [type]              成功返回true，失败返回错误消息
     */
    function sendMail($toMail, $toName, $subject, $content, $attachment = null) {
        $this->addAddress($toMail, $toName);
        $this->Subject = $subject;
        $this->msgHTML($content);

        if ($attachment) { // 添加附件
            if (is_string($attachment)) {
                is_file($attachment) && $this->AddAttachment($attachment);
            } else if (is_array($attachment)) {
                foreach ($attachment as $file) {
                    is_file($file) && $this->AddAttachment($file);
                }
            }
        }
        if (!$this->send()) { // 发送
            return $this->ErrorInfo;
        } else {
            return true;
        }
    }
}

//class Mail extends PHPMailer{
//
//}