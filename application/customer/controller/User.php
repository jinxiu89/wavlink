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
use app\common\model\Customer;
use think\App;
use app\common\service\customer\User as Service;
use app\customer\validate\User as Validate;

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
        $this->service=new Service();
        $this->validate=new Validate();
    }
    protected $middleware=[];

    /**
     * login
     * 会员登录控制器
     * 已严格代码
     */
    public function login(){
        if(request()->isGet()){
            return $this->fetch();
        }
        if(request()->isPost()){
            $data = input('post.',[],'htmlspecialchars,trim');
            $login_url=url('customer_login');
            $index=url('customer_index');
            if(!$this->validate->scene('login')->check($data)){
                return show(0,$this->validate->getError(),'','',$login_url,'');
            }
            $result=$this->service->login($data);
            if(!empty($index)){
                return show($result['status'],$result['message'],'','',$index,'');
            }
            return show($result['status'],$result['message'],'','',$result['url'],'');
        }
    }

    /**
     * 会员登出控制器
     * 只有在登入的情况下才能执行登出操作
     */
    public function logout(){
        //清除session
        session(null, 'Customer');
        //跳出
        $this->redirect(url('customer_login'));
    }

    /**
     * 会员找回密码控制器
     */
    public function forgotten(){
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
                    <p></p><img src=\"https://www.wavlink.com/static/frontend/zh-cn/img/wavlink-logo.png\" alt=\"https://www.wavlink.com\",height=\"80px\" width=\"360px\"/>
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

    /**
     * @param string $email
     * 通过邮箱重设密码
     * @return mixed|void
     */
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

    /**
     * 会员注册控制器
     */
    public function register(){
        if($this->request->isGet()){
            return $this->fetch();
        }
        if($this->request->isPost()){
            $data = input('post.',[],'trim,htmlspecialchars');
            if (!captcha_check($data['captcha'])) {
                return show(0, lang('Verification Error'), '', '', '', lang('The security code is invalid.'));
            }
            $user=new \app\common\model\Customer();
            //先验证是否这个邮箱是否有注册，如果是就不应该让他注册两次哇   逗比：你的AOP在哪里？
            $result = $this->service->reg($data);
            if ($result == false){
                return show('0',lang('Already existed'),'','','/customer/login','');
            }else{
                return show(1,lang('ok'),'','','/customer/login');
            }
        }
    }

    public function info(){
        $id = session('CustomerInfo', '', 'Customer');
        if(request()->isGet()){
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
    public function modifyInfo(){

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