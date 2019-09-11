<?php
/**
 * Created by PhpStorm.
 * User: web jinxiu89@163.com
 * Date: 2018/8/15
 * Time: 15:09
 * 命名规范：文件名首字母大写
 * 类 首写字母大写
 * 函数 驼峰命名  如getInfo
 */

namespace app\customer\controller;


class Logout extends Base_reg
{
    public function index()
    {
        //清除session
        session(null, 'Customer');
        //跳出
        $this->redirect('/customer/login.html');
    }
}