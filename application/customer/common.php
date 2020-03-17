<?php
use \think\facade\Session;
use \think\facade\Request;
use think\facade\Config;
/**
 * @return mixed
 * 从浏览器得到语言
 */
/*function Get_Lang(){
    $AcceptLanguage = Request::header();
    $lang_code = $AcceptLanguage['accept-language'];
    $code = explode(',',$lang_code);
    return $code[0];
}*/
/*function getLang(){
    //如果有模块设置的语言session.就取session里设置的模块名做语言
    if (Session::has('langModel','Customer')) {
        $lang = Session::get('langModel', 'Customer');
    } else {
        //如果没有模块设置的语言session,就表明他是异常登录，拿到浏览器的语言，初始化语言项
        $res = Get_Lang();
        $lang = Config::get($res);
    }
    return $lang;
}*/

///**
// * @param $toMail
// * @param $toName
// * @param $subject
// * @param $content
// * @param null $attachment
// * @return bool 阿里云的smtp邮件发送
// * 阿里云的smtp邮件发送
// * @throws phpmailerException
// * @internal param $to
// * 采用 阿里云的发件服务器来发送邮件
// */
//function sendMail($toMail, $toName, $subject, $content, $attachment = null){
//    vendor( 'phpmailer.PHPMailerAutoload' );
//    vendor('phpmailer.class#phpmailer');
//    $mail=new PHPMailer();
//    $mail->CharSet = 'utf-8';
//    $mail->IsSMTP();
//    $mail->isHTML(true);
//    $mail->SMTPDebug = 0;
//    $mail->Debugoutput = 'html';
//    $mail->Host = 'smtpdm-ap-southeast-1.aliyun.com';                         // SMTP服务器地址
//    $mail->Port = 465;                         // 端口号
//    $mail->SMTPAuth = true;                // SMTP登录认证
//    $mail->SMTPSecure = 'ssl';            // SMTP安全协议
//    $mail->Username = 'system@service.wavlink.us';                 // SMTP登录邮箱
//    $mail->Password = 'Wh32Ym69B10c';                 // SMTP登录密码
//    $mail->setFrom('system@service.wavlink.us', 'system');            // 发件人邮箱和名称
//    $mail->addReplyTo('content@wavlink.com', 'Wavlink.com'); // 回复邮箱和名称
//    $mail->AddAddress($toMail,$toName);
//    $mail->Subject = $subject;
//    $mail->Body=$content;
//    if ($attachment) { // 添加附件
//        if (is_string($attachment)) {
//            is_file($attachment) && $mail->AddAttachment($attachment);
//        } else if (is_array($attachment)) {
//            foreach ($attachment as $file) {
//                is_file($file) && $mail->AddAttachment($file);
//            }
//        }
//    }
//    $result = $mail->send();
//    return $result ? true : $mail->ErrorInfo;
//}
