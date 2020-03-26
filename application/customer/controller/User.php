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

    /**
     * 会员登录控制器
     */
    public function login(){
        if($this->request->isGet()){
            return $this->fetch();
        }
        if($this->request->isPost()){
            //todo::登录操作
            $data = input('post.',[],'htmlspecialchars,trim');
            print_r($data);exit;
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
     */
    public function logout(){

    }

    /**
     * 会员找回密码控制器
     */
    public function forgotten(){

    }

    /**
     * 会员注册控制器
     */
    public function register(){
        if($this->request->isGet()){
            return $this->fetch();
        }
        if($this->request->isPost()){
            //todo::注册操作
        }
    }

    /**
     * 会员信息修改控制器
     */
    public function modifyInfo(){

    }

}