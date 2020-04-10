<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/3/26 15:48
 * @User: admin
 * @Current File : User.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\customer\controller;

use app\common\model\Country;
use app\common\service\customer\User as Service;
use app\customer\validate\User as Validate;
use think\App;
use think\facade\Cache;
use app\customer\middleware\Auth;
use think\facade\Config;
use think\Route;

/**
 * Class User
 * @package app\customer\controller
 * 会员基本信息
 */
class User extends Base
{
    /**
     * User constructor.
     * @param App|null $app
     *
     */
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->service = new Service();
        $this->validate = new Validate();
    }

    protected $middleware = [
        Auth::class=>['except'=>['login','register','forgotPassword']]
    ];

    /**
     * login
     * 会员登录控制器
     * 已严格代码
     */
    public function login()
    {
        if (request()->isGet()) {
            return $this->fetch();
        }
        if (request()->isPost()) {
            $data = input('post.', [], 'htmlspecialchars,trim');
            if(!Config::get('app.app_debug')){/*如果开启了debug 就不走验证，不开启就走验证码*/
                if (!captcha_check($data['captcha'])) {
                    return show(0, '', '', '', '', '验证码错误');
                }
            }
            $login_url = url('customer_login');
            $index = url('customer_index');
            $scene = empty($data['email']) ? 'phone' : 'email';
            if (!$this->validate->scene($scene)->check($data)) {
                return show(0, $this->validate->getError(), '', '', $login_url, '');
            }
            $result = $this->service->login($data);
            if (!empty($index)) {
                return show($result['status'], $result['message'], '', '', $index, '');
            }
            return show($result['status'], $result['message'], '', '', $result['url'], '');
        }
    }

    /**
     * 会员登出控制器
     * 只有在登入的情况下才能执行登出操作
     */
    public function logout()
    {
        //清除session
        session(null, 'Customer');
        //跳出
        $this->redirect(url('customer_login'));
    }

    public function forgotPassword(){
        if(request()->isGet()){
            return $this->fetch();
        }
        if(request()->isPost()){

        }
    }

    /**
     * 会员找回密码控制器
     */
    public function forgotten()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            if (!captcha_check($data['captcha'])) {
                return show(0, lang('Verification Error'), '', '', '', '验证码错误');
            }
            $email = (new Customer())->CheckEmail($data['email']);
            $url = request()->domain() . "/customer/reset/" . $data['email'];
            $dear = lang('Dear');
            $welcome = lang('Welcome to wavlink');
            $message = lang('Your email address is ') . ' ' . $data['email'];
            $please = lang('Please');
            $click = lang('Click me');
            $reset = lang('Reset your password');
            $ifNot = lang('If the above connection cannot be opened');
            $noreplay = lang('noreplay');
            $sup = lang('Wavlink Support');
            if ($email == true) {
                $content = "
                    <div>
                    <p><strong>lang('Dear')</strong></p>
                    <p>lang('Welcome to wavlink')</p>
                    <p>$message</p>
                    <p>$please <a href='$url'>$click</a>$reset</p>
                    $ifNot
                    <p>$url</p>
                    </div>
                    <div style=\"border-top:1px solid #ccc;padding:10px 0;font-size:12px;margin:20px 0;color:#A0A0A0;\" >
                    <p>$noreplay</p>
                    <b>$sup</b>
                    <p></p><img src=\"https://www.wavlink.com/static/frontend/zh-cn/img/wavlink-logo.png\" alt=\"https://www.wavlink.com\",height=\"80px\" width=\"360px\"/>
                    </div>
                    ";
                $subject = lang('Retrieve your password');
                $result = sendMail($data['email'], '', $subject, $content);
                if ($result) {
                    return show(1, lang('Send Mail is Success'), '', '', '/customer/login.html', lang('Message Sent Successfully!'));
                } else {
                    return show(0, lang('Send Error'), '', '', '', lang('Message Sent Failed!'));
                }
            } elseif ($email == false) {
                return show(0, lang('User does not exist'), '', '', '', '');
            }
        }
        return $this->fetch();
    }

    /**
     * @param string $email
     * 通过邮箱重设密码
     * @return mixed|void
     */
    public function reset($email = '')
    {
        if (!isset($email) || empty($email)) {
            abort(404);
        }
        if (request()->isAjax()) {
            $data = input('post.');
            if (!captcha_check($data['captcha'])) {
                return show(0, lang('Verification Error'), '', '', '', '');
            }
            //ID 根据 email  返回他的ID
            $user = new Customer();
            $id = $user->getNameByEmail($email);
            $result = $user->upDateById($data, $id);
            if ($result) {
                return show(1, lang('Ok'), '', '', '/customer/login.html', '');
            } elseif ($result == false) {
                return show(0, lang('User does not exist'), '', '', '', '');
            } else {
                return show(0, lang('User does not exist'), '', '', '', '');
            }
        }
        return $this->fetch();
    }

    /**
     * 会员注册控制器
     * 分为两种注册方式 type=1时 表示为email注册 type=2时表示为手机号注册
     */
    public function register()
    {
        if ($this->request->isGet()) {
            return $this->fetch();
        }
        if ($this->request->isPost()) {
            $data = input('post.', [], 'trim,htmlspecialchars');
            $data['password'] = GetPassword($data['password']);
            if ($data['type'] == 1) { //email 注册
                $code = Cache::store('redis')->get($data['email'], '') ? Cache::store('redis')->get($data['email'], '') : Cache::store('default')->get($data['email'], '');
                if ($code != $data['verification']) {
                    return show(0, lang('The verification code is invalid'), '', '', '', lang('The verification code is invalid'));
                }
                $check = $this->service->CheckEmail($data['email']);
                if ($check == true) {
                    return show(0, lang('Your Email address Already existed'), '', '', '', lang('Your Email address Already existed'));
                }
                if (is_string($check)) {
                    return show(0, lang('Server Error'), '', '', '', lang('Server Error'));
                }
            }
            if ($data['type'] == 2) { //短信注册
                $code = Cache::store('redis')->get($data['phone'], '') ? Cache::store('redis')->get($data['phone'], '') : Cache::store('default')->get($data['phone'], '');
                if ($code != $data['verification']) {
                    return show(0, lang('The verification code is invalid'), '', '', '', lang('The verification code is invalid'));
                }
                $check = $this->service->CheckPhone($data['phone']);
                if ($check == true) {
                    return show(0, lang('Your phone  Already existed'), '', '', '', lang('Your phone  Already existed'));
                }
                if (is_string($check)) {
                    return show(0, lang('Server Error'), '', '', '', lang('Server Error'));
                }
            }
            $instance = $this->service->create($data); //instance 是实例的意思
            if ($instance->id) {//注册第二步，填写产品信息
                return show(1, lang('Success'), '', '', url('customer_product_register', ['user_id' => $instance->id]), lang('Successfully!'));
            } else {
                return show(0, lang('Error'), '', '', '', lang('Failed!'));
            }
        }
    }


    public function info()
    {
        $id = session('CustomerInfo', '', 'Customer');
        if (request()->isGet()) {
            $customer = $this->service->getDataByIdWithInfo($id);
            $country = (new Country())->field('country_id,name')->select();
            if (isMobile()) {
                return "hello world";
            } else {
                return $this->fetch('', [
                    'country' => $country,
                    'result' => $customer
                ]);
            }
        }
        /*if (request()->isAjax()) {
            $data = input('post.',[],'htmlspecialchars');
            $result = $user->upDateById($data, $id);
            if ($result == true) {
                return show(1, lang('Success'), '', '', url('customer_info'));
            } else {
                return show(0, lang('Success'), '', '', url('customer_info'));
            }
        }*/

    }


    /**
     * 会员信息修改控制器
     */
    public function modifyInfo()
    {

    }

    /**
     * editPassword 登录用户，修改密码
     */
    public function editPassword()
    {
        $id = session('CustomerInfo', '', 'Customer');
        if (request()->isAjax()) {
            $data = input('post.');
            //核对老密码
            $user = new Customer();
            $oldPassword = $data['oldPassword'];
            $result = $user->CheckPassword($oldPassword, $id);
            if ($result == true) {
                $result = $user->upDateById($data, $id);
            } else {
                return show(0, lang('Old Password input Error'), '', '', '', '');
            }
            if ($result == true) {
                return show(1, lang('Success'), '', '', url('customer_info'));
            } else {
                return show(0, lang('Success'), '', '', url('customer_info'));
            }
        }
    }
}