<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/3/14
 * Time: 11:19
 */
namespace app\customer\controller;
use think\Controller;
use think\Lang;
class Base extends Controller
{
    public function _initialize() {
        $lang = getLang();
        Lang::load(APP_PATH . 'customer/lang/' . $lang . '.php'); //加载该语言下的模块语言包
        if(!$this->isLogin()){
            $this->redirect('customer/Login/index');
        }
        $this->assign('lang',$lang);
    }

    public function isLogin(){
        //获取session
        $customer = $this->getLoginCustomer();
        if ($customer){
            return true;
        }
        return false;
    }
    public function getLoginCustomer(){
        $customer = session('CustomerInfo','','Customer');
        return $customer;
    }
}