<?php
/**
 * Created by PhpStorm.
 * User: wavlink
 * Date: 2016/11/22
 * Time: 10:39
 */
namespace app\en_us\controller;

use think\Request;
use User\Api\UserApi;

class User extends Base
{
    public function index(){
       return "ehllo";
    }

    /**
     *登录
     * @param Request $request
     * @return string
     */
    public function login(Request $request)
    {
        //
        if (!request()->isPost()){
            return show(0,L('Login Failed'),$url='login/index');
        }
        //获取用户输入的邮箱和密码
        $data = $request::instance()->post();
        //用户登录认证
        $user = new \app\vistor\api\User();
        $User = $user->login($data);
        if (is_array($User)){
            return show(1,L("Success"),L('OK'),L('OK'),$url = 'user/index');
        }

    }

}