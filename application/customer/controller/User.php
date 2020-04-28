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
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\facade\Cache;
use app\customer\middleware\Auth;
use think\facade\Config;

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
        Auth::class => ['except' => ['login', 'register', 'forgotPassword', 'changePassword']]
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
            if (!Config::get('app.app_debug')) {/*如果开启了debug 就不走验证，不开启就走验证码*/
                if (!captcha_check($data['captcha'])) {
                    return show(0, '', '', '', '', lang('The verification code is invalid'));
                }
            }
            $login_url = url('customer_login');
            $index = url('customer_info');
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

    /**3.
     * @return mixed|void
     */
    public function forgotPassword()
    {
        if (request()->isGet()) {
            return $this->fetch();
        }
        if (request()->isPost()) {
            $data = input('post.', [], 'htmlspecialchars,trim');
            if ($data['type'] == 1) {
                $code = Cache::store('redis')->get($data['email'], '') ? Cache::store('redis')->get($data['email'], '') : Cache::store('default')->get($data['email'], '');
                if ($data['captcha'] != $code) {
                    return show(0, lang('The verification code is invalid'), '', '', '', lang('The verification code is invalid'));
                }
                $user = $this->service->getUserByEmail($data['email']);
                if (!empty($user)) {
                    return show(1, lang('Wait a moment'), '', '', url('change_password', ['id' => $user['id'], 'email' => $user['email']]));
                }
            }
            if ($data['type'] == 2) {
                $code = Cache::store('redis')->get($data['phone'], '') ? Cache::store('redis')->get($data['phone'], '') : Cache::store('default')->get($data['phone'], '');
                if ($data['captcha'] != $code) {
                    return show(0, lang('The verification code is invalid'), '', '', '', lang('The verification code is invalid'));
                }
                $user = $this->service->getUserByPhone($data['phone']);
                if (!empty($user)) {
                    return show(1, lang('Wait a moment'), '', '', url('change_password', ['id' => $user['id'], 'phone' => $user['phone']]));
                }
            }

            return show(0, lang('The user does not exist'), '', '', '', lang('The user does not exist'));
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
                if ($code != $data['captcha']) {
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
                if ($code != $data['captcha']) {
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

    /**
     * @return mixed|string
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     * 我的个人资料
     */
    public function info()
    {
        if (request()->isGet()) {
            $customer = $this->service->getDataByIdWithInfo($this->uid)->toArray();
            $country = (new Country())->field('country_id,name')->select();
            unset($customer['password'], $customer['referee_code'], $customer['create_time'], $customer['update_time'], $customer['is_subscribe'], $customer['disclaimer']);
            if (isMobile()) {
                return "hello world";
            } else {
                return $this->fetch('', [
                    'country' => $country->toArray(),
                    'result' => $customer
                ]);
            }
        }

    }

    /***
     * 修改名字
     * @return mixed
     */
    public function changeName()
    {
        if ($this->request->isGet()) {
            $data = $this->service->getInfo($this->uid);
            $name = [];
            if (empty($data->info)) {
                $name['first_name'] = '';
                $name['last_name'] = '';
            } else {
                $name['first_name'] = $data->info->first_name;
                $name['last_name'] = $data->info->last_name;
            }
            unset($data);
            $this->assign('name', $name);
            return $this->fetch();
        }
        if ($this->request->isPost()) {
            $data = input('post.', [], 'trim,htmlspecialchars');
            if (!$this->validate->scene('changeName')->check($data)) {
                return show(0, $this->validate->getError(), '', '', '', $this->validate->getError());
            }
            $result = $this->service->changeInfo($data);
            if (true == $result) {
                return show(1, lang('Success'), '', '', url('customer_info'));
            } elseif (false == $result) {
                return show(0, lang('失败'), '', '');
            } else {
                return show(0, lang($result), '', '');
            }
        }
    }

    /**
     * @return mixed
     * 改变性别
     */
    public function changeGender()
    {
        if ($this->request->isGet()) {
            $data = $this->service->getInfo($this->uid);
            $gender = '';
            if (!empty($data->info)) {
                $gender = $data->info->gender;
            }
            unset($data);
            $this->assign('gender', $gender);
            return $this->fetch();
        }
        if ($this->request->isPost()) {
            $data = input('post.', [], 'trim,htmlspecialchars');
            if (!$this->validate->scene('changeGender')->check($data)) {
                return show(0, $this->validate->getError(), '', '', '', $this->validate->getError());
            }
            $result = $this->service->changeInfo($data);
            if (true == $result) {
                return show(1, lang('Success'), '', '', url('customer_info'));
            } elseif (false == $result) {
                return show(0, lang('失败'), '', '');
            } else {
                return show(0, lang($result), '', '');
            }
        }
    }

    /**
     *
     * @return mixed
     * 修改生日
     */
    public function changeBirthday()
    {
        if ($this->request->isGet()) {
            $data = $this->service->getInfo($this->uid);
            $birthday = '';
            if (!empty($data->info)) $birthday = $data->info->birthday;
            unset($data);
            $this->assign('birthday', $birthday);
            return $this->fetch();
        }
        if ($this->request->isPost()) {
            $data = input('post.', [], 'trim,htmlspecialchars');
            if (!$this->validate->scene('changeBirth')->check($data)) {
                return show(0, $this->validate->getError(), '', '', '', $this->validate->getError());
            }
            $result = $this->service->changeInfo($data);
            if (true == $result) {
                return show(1, lang('Success'), '', '', url('customer_info'));
            } elseif (false == $result) {
                return show(0, lang('失败'), '', '');
            } else {
                return show(0, lang($result), '', '');
            }
        }
    }

    /***
     * @return mixed|void
     */
    public function changePhone()
    {
        if ($this->request->isGet()) {
            $data = $this->service->getInfo($this->uid);
            $phone = $data->phone;
            unset($data);
            $this->assign('phone', $phone);
            return $this->fetch();
        }
        if ($this->request->isPost()) {
            $data = input('post.', [], 'trim,htmlspecialchars');
            if (!$this->validate->scene('changePhone')->check($data)) {//todo:计划加上海外手机的匹配
                return show(0, $this->validate->getError(), '', '', '', $this->validate->getError());
            }
            $code = Cache::store('redis')->get($data['phone'], '') ? Cache::store('redis')->get($data['phone'], '') : Cache::store('default')->get($data['phone'], '');
            if ($code != $data['captcha']) {
                return show(0, lang('The verification code is invalid'), '', '', '', lang('The verification code is invalid'));
            }
            $result = $this->service->updateInfo($data);
            if (true == $result) {
                return show(1, lang('Success'), '', '', url('customer_info'));
            } elseif (false == $result) {
                return show(0, lang('失败'), '', '');
            } else {
                return show(0, lang($result), '', '');
            }
        }
    }

    /***
     * @return mixed|void
     */
    public function changeEmail()
    {
        if ($this->request->isGet()) {
            $data = $this->service->getInfo($this->uid);
            $email = $data->email;
            unset($data);
            $this->assign('email', $email);
            return $this->fetch();
        }
        if ($this->request->isPost()) {
            $data = input('post.', [], 'trim,htmlspecialchars');
            if (!$this->validate->scene('changeEmail')->check($data)) {
                return show(0, $this->validate->getError(), '', '', '', $this->validate->getError());
            }
            $code = Cache::store('redis')->get($data['email'], '') ? Cache::store('redis')->get($data['email'], '') : Cache::store('default')->get($data['email'], '');
            if ($code != $data['captcha']) {
                return show(0, lang('The verification code is invalid'), '', '', '', lang('The verification code is invalid'));
            }
            $result = $this->service->updateInfo($data);
            if (true == $result) {
                return show(1, lang('Success'), '', '', url('customer_info'));
            } elseif (false == $result) {
                return show(0, lang('失败'), '', '');
            } else {
                return show(0, lang($result), '', '');
            }
        }
    }

    /**
     * @return mixed|void
     * changeCountry 用户修改自己的所属国家
     */
    public function changeCountry()
    {
        if ($this->request->isGet()) {
            $country = $this->service->getCountry();
            $data = $this->service->getInfo($this->uid);
            if (empty($data->info)) {
                $country_id = '';
            } else {
                $country_id = $data->info->country;
            }
            $this->assign('country', $country);
            $this->assign('country_id', $country_id);
            return $this->fetch();
        }
        if ($this->request->isPost()) {
            $data = input('post.', [], 'trim,htmlspecialchars');
            if (!$this->validate->scene('changeCountry')->check($data)) {
                return show(0, $this->validate->getError(), '', '', '', $this->validate->getError());
            }
            if (!$data[''])
                $result = $this->service->changeInfo($data);
            if (true == $result) {
                return show(1, lang('Success'), '', '', url('customer_info'));
            } elseif (false == $result) {
                return show(0, lang('失败'), '', '');
            } else {
                return show(0, lang($result), '', '');
            }
        }
    }

    /**
     * @return mixed|void
     * changeCountry 用户修改自己的所属国家
     */
    public function changeCode()
    {
        if ($this->request->isGet()) {
            $data = $this->service->getInfo($this->uid);
            $code = '';
            if (!empty($data->info)) $code = $data->info->zip_code;
            unset($data);
            $this->assign('code', $code);
            return $this->fetch();
        }
        if ($this->request->isPost()) {
            $data = input('post.', [], 'trim,htmlspecialchars');
            if (!$this->validate->scene('changeCode')->check($data)) {
                return show(0, $this->validate->getError(), '', '', '', $this->validate->getError());
            }
            $result = $this->service->changeInfo($data);
            if (true == $result) {
                return show(1, lang('Success'), '', '', url('customer_info'));
            } elseif (false == $result) {
                return show(0, lang('失败'), '', '');
            } else {
                return show(0, lang($result), '', '');
            }
        }
    }

    /**
     * @return mixed|void
     */
    public function changeBillingAddress()
    {
        if ($this->request->isGet()) {
            $data = $this->service->getInfo($this->uid);
            $billing_address = '';
            if (!empty($data->info)) $billing_address = $data->info->billing_address;
            unset($data);
            $this->assign('billing_address', $billing_address);
            return $this->fetch();
        }
        if ($this->request->isPost()) {
            $data = input('post.', [], 'trim,htmlspecialchars');
            if (!$this->validate->scene('changeBillAddress')->check($data)) {
                return show(0, $this->validate->getError(), '', '', '', $this->validate->getError());
            }
            $result = $this->service->changeInfo($data);
            if (true == $result) {
                return show(1, lang('Success'), '', '', url('customer_info'));
            } elseif (false == $result) {
                return show(0, lang('失败'), '', '');
            } else {
                return show(0, lang($result), '', '');
            }
        }
    }

    /**
     * @return mixed|void
     */
    public function changeDeliveryAddress()
    {
        if ($this->request->isGet()) {
            $data = $this->service->getInfo($this->uid);
            $delivery_address = '';
            if (!empty($data->info)) $delivery_address = $data->info->delivery_address;
            unset($data);
            $this->assign('delivery_address', $delivery_address);
            return $this->fetch();
        }
        if ($this->request->isPost()) {
            $data = input('post.', [], 'trim,htmlspecialchars');
            if (!$this->validate->scene('changeDeliveryAddress')->check($data)) {
                return show(0, $this->validate->getError(), '', '', '', $this->validate->getError());
            }
            $result = $this->service->changeInfo($data);
            if (true == $result) {
                return show(1, lang('Success'), '', '', url('customer_info'));
            } elseif (false == $result) {
                return show(0, lang('失败'), '', '');
            } else {
                return show(0, lang($result), '', '');
            }
        }
    }

    /***
     * changePassword 在没有登录的情况下 找回密码 修改密码通过该路由
     * @return mixed
     */
    public function changePassword()
    {
        if ($this->request->isGet()) {
            $email = input('get.email', '', 'htmlspecialchars,trim');
            $phone = input('get.phone', '', 'htmlspecialchars,trim');
            if (isset($email) && !empty($email)) $this->assign('email', $email);
            if (isset($phone) && !empty($phone)) $this->assign('phone', input('get.phone', '', 'htmlspecialchars,trim'));
            return $this->fetch('');
        }
        if ($this->request->isPost()) {
            $data = input('post.', '', 'htmlspecialchars,trim');
            if (!$this->validate->scene('change_password')->check($data)) {
                return show(0, $this->validate->getError(), '', '', '', $this->validate->getError());
            }
            $code = '';
            if ($data['type'] == 1) {
                $code = Cache::store('redis')->get($data['email'], '') ? Cache::store('redis')->get($data['email'], '') : Cache::store('default')->get($data['email'], '');
            }
            if ($data['type'] == 2) {
                $code = Cache::store('redis')->get($data['phone'], '') ? Cache::store('redis')->get($data['phone'], '') : Cache::store('default')->get($data['phone'], '');
            }
            if ($code != ($data['captcha'])) {
                return show(0, lang('The verification code is invalid'), '', '', '', lang('The verification code is invalid'));
            }
            $data['password'] = GetPassword($data['password']);
            if ($this->service->updateData($data)) {
                return show(1, lang('success'), '', '', url('customer_login'), lang('success'));
            }
        }
    }

    /**
     * editPassword 登录用户，修改密码
     */
    public function resetPassword()
    {
        $data = $this->service->getInfo($this->uid);
        if ($this->request->isGet()) {
            $this->assign('password', $data->password);
            return $this->fetch();
        }
        if (request()->isAjax()) {
            $data = input('post.', [], 'trim,htmlspecialchars');
            if (!$this->validate->scene('change_password')->check($data)) {
                return show(0, $this->validate->getError(), '', '', '', $this->validate->getError());
            }
            if (!empty($data['old_password']) and GetPassword($data['old_password']) != $data->password) {
                return show(0, lang('old password is error'), '', '');
            }
            $data['password'] = GetPassword($data['password']);
            $data['id'] = $this->uid;
            $result = $this->service->updateInfo($data);
            if (true == $result) {
                return show(1, lang('Success'), '', '', url('customer_logout'));
            } elseif (false == $result) {
                return show(0, lang('失败'), '', '');
            } else {
                return show(0, lang($result), '', '');
            }
        }
    }
}