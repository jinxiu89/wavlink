<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/12/9
 * Time: 14:42
 */
namespace app\en_us\controller;
class Login extends Base
{

    public function index(){
//        $module = request()->module();
//        Session::clear('Customer');
//        Session::set('langModel',$module,'Customer');
//        $this->redirect(url('customer/Login/index'),'',302,['lang' => $module]);
        return view($this->template.'/common/404.html');
    }
}