<?php
namespace app\en_us\controller;

use think\Controller;
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
        return redirect('/' . $code . '/index.html', [], 200);
    }
}