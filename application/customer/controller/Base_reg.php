<?php
/**
 * Created by PhpStorm.
 * User: web jinxiu89@163.com
 * Date: 2018/8/16
 * Time: 15:33
 * 命名规范：文件名首字母大写
 * 类 首写字母大写
 * 函数 驼峰命名  如getInfo
 */

namespace app\customer\controller;

use think\Controller;
use think\Lang;

class Base_reg extends Controller
{
    public function _initialize()
    {
        $lang = getLang();
        Lang::load(APP_PATH . 'customer/lang/' . $lang . '.php');//加载该语言下的模块语言包
        $this->assign("lang", $lang);  //给页面一个语言变量，来却确认是否加载验证层以及其他前端语言模块的文件
    }
}