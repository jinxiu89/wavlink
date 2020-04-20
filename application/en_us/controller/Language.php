<?php
namespace app\en_us\controller;

use think\Controller;
use think\facade\Config;
use think\facade\Cookie;
use think\response\Redirect;

/**
 * Class Language
 * @package app\en_us\controller
 */
class Language extends Controller
{
    /***
     * @param $code
     * @return Redirect
     * 手动设置语言
     *
     */
    public function setLanguage($code)
    {
        if(in_array($code,Config::get('language.allow_lang'))){
            Cookie::set('lang_var',$code);
        }else{
            Cookie::set('lang_var', 'en_us');
        }
        return redirect('/' . $code . '/index.html', [], 200);
    }
}