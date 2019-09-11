<?php
/**
 * Created by PhpStorm.
 * User: web jinxiu89@163.com
 * Date: 2018/8/21
 * Time: 9:19
 * 命名规范：文件名首字母大写
 * 类 首写字母大写
 * 函数 驼峰命名  如getInfo
 */

namespace app\customer\controller;


use app\common\model\Customer;
use think\Config;

class Forgotten extends Base_reg
{
    //找回密码

    public function forgotten()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            if (!captcha_check($data['captcha'])) {
                return show(0, lang('Verification Error'), '', '', '', '验证码错误');
            }
            $email = (new Customer())->CheckEmail($data['email']);
            $url=request()->domain()."/customer/reset/".$data['email'];
            $dear=lang('Dear');
            $welcome=lang('Welcome to wavlink');
            $message=lang('Your email address is ').' '.$data['email'];
            $please=lang('Please');
            $click=lang('Click me');
            $reset=lang('Reset your password');
            $ifNot=lang('If the above connection cannot be opened');
            $noreplay=lang('noreplay');
            $sup=lang('Wavlink Support');
            if ($email == true) {
                $content = "
                    <div>
                    <p><strong>$dear</strong></p>
                    <p>$welcome</p>
                    <p>$message</p>
                    <p>$please <a href='$url'>$click</a>$reset</p>
                    $ifNot
                    <p>$url</p>
                    </div>
                    <div style=\"border-top:1px solid #ccc;padding:10px 0;font-size:12px;margin:20px 0;color:#A0A0A0;\" >
                    <p>$noreplay</p>
                    <b>$sup</b>
                    <p></p><image src=\"https://www.wavlink.com/static/frontend/zh-cn/img/wavlink-logo.png\" alt=\"https://www.wavlink.com\",height=\"80px\" width=\"360px\"/>
                    </div>
                    ";
                $subject = lang('Retrieve your password');
                $result = sendMail($data['email'], '', $subject, $content);
                if ($result){
                    return show(1,lang('Send Mail is Success'),'','','/customer/login.html',lang('Message Sent Successfully!'));
                }else{
                    return show(0,lang('Send Error'),'','','',lang('Message Sent Failed!'));
                }
            } elseif ($email == false) {
                return show(0, lang('User does not exist'), '', '', '', '');
            }
        }
        return $this->fetch();
    }
    public function reset($email=''){
        if (!isset($email) || empty($email)) {
            abort(404);
        }
        if(request()->isAjax()){
            $data=input('post.');
            if (!captcha_check($data['captcha'])) {
                return show(0, lang('Verification Error'), '', '', '', '');
            }
            //ID 根据 email  返回他的ID
            $user=new Customer();
            $id=$user->getNameByEmail($email);
            $result=$user->upDateById($data,$id);
            if($result){
                return show(1,lang('Ok'),'','','/customer/login.html','');
            }elseif ($result == false){
                return show(0, lang('User does not exist'), '', '', '', '');
            }else{
                return show(0, lang('User does not exist'), '', '', '', '');
            }
        }
        return $this->fetch();
    }

}