<?php
/**
 * Created by PhpStorm.
 * User: web  jinxiu89@163.com
 * Date: 2018/8/15
 * Time: 11:24
 * 命名规范：文件名首字母大写
 * 类 首写字母大写
 * 函数 驼峰命名  如getInfo
 */

namespace app\customer\controller;

use app\common\model\Customer as User;

class Register extends Base_reg
{
    /***
     * 注册页面渲染
     */
    public function index()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            if (!captcha_check($data['captcha'])) {
                return show(0, lang('Verification Error'), '', '', '', lang('The security code is invalid.'));
            }
            $user=new User();
            //先验证是否这个邮箱是否有注册，如果是就不应该让他注册两次哇   逗比：你的AOP在哪里？
            $data = input('post.');
            $result = $user->reg($data);
            if ($result == false){
                return show('0',lang('Already existed'),'','','/customer/login','');
            }else{
                return show(1,lang('ok'),'','','/customer/login');
            }
        }
        return $this->fetch();
    }
    public function sendCode($email){

    }

    public function weiXin()
    {
        //todo::微信第三方登录
    }

    public function qq()
    {
        //todo::qq第三方登录
    }

    public function facebook()
    {
        //todo::facebook 第三方登录
    }


}