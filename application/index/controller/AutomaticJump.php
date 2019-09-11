<?php
namespace app\index\controller;

use think\Controller;

/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/5/8
 * Time: 18:13
 * 获取当前的浏览器的语言
 * 根据语言来获取
 */
class AutomaticJump extends Controller
{
    public function index() {
        $code = autoGetLang();
        $lang = config('lang.'.substr($code,0,2));
        if($lang){
            $this->redirect(url('/'.$lang.'/index'));
        }else{
            $this->redirect(url('/en_us/index'));
        }
    }
}
