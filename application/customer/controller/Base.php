<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/3/14
 * Time: 11:19
 */
namespace app\customer\controller;
use think\App;
use think\Controller;
use think\facade\Cookie;
use think\facade\Lang;
class Base extends Controller
{
    protected $service;
    protected $validate;
    public function __construct(App $app = null)
    {
        parent::__construct($app);
    }

    public function initialize() {
        $lang = $lang = Cookie::get('lang_var') ? Cookie::get('lang_var'): 'en_us';
        Lang::load(APP_PATH . 'customer/lang/' . $lang . '.php'); //加载该语言下的模块语言包
        $this->assign('lang',$lang);
    }

    /**
     * @return bool
     */
    public function isLogin(){
        //获取session
        $customer = session('CustomerInfo','','Customer');
        if ($customer){
            return true;
        }
        return false;
    }
}