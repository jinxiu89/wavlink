<?php


namespace app\en_us\controller;


use think\Controller;
use think\Facade\Env;

/**
 * Class Error
 * @package app\en_us\controller
 */
class Error extends Base
{
    /**
     * @return string
     */
    public function index()
    {
        return view(Env::get('APP_PATH').'/en_us/view/error/404.html', [], $code = 404);
    }
}