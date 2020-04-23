<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/3/14
 * Time: 11:19
 */

namespace app\customer\controller;

use app\lib\utils\tools;
use app\lib\utils\sms;
use app\lib\utils\email;
use Exception;
use think\App;
use think\captcha\Captcha;
use think\Controller;
use think\facade\Cache;
use think\facade\Config;
use think\facade\Cookie;
use think\facade\Lang;
use think\Response;

class Base extends Controller
{
    protected $service;
    protected $validate;
    protected $uid;

    public function __construct(App $app = null)
    {

        parent::__construct($app);
        $this->uid = session('CustomerInfo', '', 'Customer');
    }

    public function initialize()
    {
        $lang = $lang = Cookie::get('lang_var') ? Cookie::get('lang_var') : 'en_us';
        Lang::load(APP_PATH . 'customer/lang/' . $lang . '.php'); //加载该语言下的模块语言包
        $this->assign('lang', $lang);
    }



    /**
     * @return bool
     */
    public function isLogin()
    {
        //获取session
        $customer = session('CustomerInfo', '', 'Customer');
        if ($customer) {
            return true;
        }
        return false;
    }

    /**
     * sendVerification 发送验证码
     * 传递参数说明：1.phone: 手机注册 2.email 注册；如果email和phone 都传递过来，则走email注册
     */
    public function sendVerification()
    {
        $str = tools::GetIntStr(6);
        if (!empty($email = input('email'))) { /*邮件验证分支*/
            try {
                Cache::store('redis')->set($email, $str, 300);
            } catch (Exception $exception) {
                Cache::store('default')->set($email, $str, 300);
            }
            $dear = lang('Dear');
            $welcome = lang('Welcome to wavlink');
            $noreplay = lang('noreplay');
            $support = lang('Wavlink Support');
            $message = lang('Your verification code  is ') . ' ' . $str;
            $content = "<div>
                    <p><strong>$dear</strong></p>
                    <p>$welcome</p>
                    <p>$message</p>
                    </div>
                    <div style=\"border-top:1px solid #ccc;padding:10px 0;font-size:12px;margin:20px 0;color:#A0A0A0;\" >
                    <p>$noreplay</p>
                    <b>$support</b>
                    <p></p><img src=\"https://s3.amazonaws.com/files.wavlink.com/images/logo.png\" alt=\"https://www.wavlink.com\",height=\"49px\" width=\"138x\"/>
                    </div>";
            $subject = lang('verification your register');
            $result = email::sendMail($email, '', $subject, $content);
            if ($result) {// 根据结果让按钮阶段性的失去点击能力
                return show(1, lang('Send Mail is Success'), '', '', '', lang('Message Send Successfully!'));
            } else {
                return show(0, lang('Send Error'), '', '', '', lang('Message Send Failed!'));
            }
        } elseif (!empty($phone = input('phone'))) {
            try {
                Cache::store('redis')->set($phone, $str, 300);
            } catch (Exception $exception) {
                Cache::store('default')->set($phone, $str, 300);
            }
            $result = sms::ali($phone, $str);
            if ($result) { // 根据结果让按钮阶段性的失去点击能力
                return show(1, lang('Send Mail is Success'), '', '', '', lang('Message Send Successfully!'));
            } else {
                return show(0, lang('Send Error'), '', '', '', lang('Message Send Failed!'));
            }
        }
    }
}