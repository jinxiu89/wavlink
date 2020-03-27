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