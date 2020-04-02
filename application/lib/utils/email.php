<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/3/30 18:09
 * @User: admin
 * @Current File : email.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\lib\utils;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as emailException;
use think\facade\Config;
/**
 * Class email
 * @package app\lib\utils
 */
class email
{
    /**
     * @param $toMail
     * @param $toName
     * @param $subject
     * @param $content
     * @param null $attachment
     * @return bool 阿里云的smtp邮件发送
     * 阿里云的smtp邮件发送
     * @throws emailException
     * @internal param $to
     */
    public static function sendMail($toMail, $toName, $subject, $content, $attachment = null)
    {
        $mail = new PHPMailer();
        $mail->CharSet = Config::get('email.charSet');
        $mail->IsSMTP();
        $mail->isHTML(true);
        $mail->SMTPDebug = Config::get('email.debug');
        $mail->Debugoutput = Config::get('mail.debug_output');
        $mail->Host = Config::get('email.host');                         // SMTP服务器地址
        $mail->Port = Config::get('email.port');                         // 端口号
        $mail->SMTPAuth = Config::get('email.auth');                // SMTP登录认证
        $mail->SMTPSecure = Config::get('email.secure');            // SMTP安全协议
        $mail->Username = Config::get('email.user');                 // SMTP登录邮箱
        $mail->Password = Config::get('email.password');                 // SMTP登录密码
        $mail->setFrom(Config::get('email.from'), Config::get('email.name'));            // 发件人邮箱和名称
        $mail->addReplyTo(Config::get('email.replay'), Config::get('email.replay_name')); // 回复邮箱和名称
        $mail->AddAddress($toMail, $toName);
        $mail->Subject = $subject;
        $mail->Body = $content;
        if ($attachment) { // 添加附件
            if (is_string($attachment)) {
                is_file($attachment) && $mail->AddAttachment($attachment);
            } else if (is_array($attachment)) {
                foreach ($attachment as $file) {
                    is_file($file) && $mail->AddAttachment($file);
                }
            }
        }
        try{
            $result = $mail->send();
            return $result ? true : $mail->ErrorInfo;
        }catch (Exception $exception){
            return $exception->getMessage();
        }
    }
}