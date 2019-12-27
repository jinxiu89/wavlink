<?php


namespace app\en_us\controller;


use think\Controller;

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
        return view($this->template . '/error/404.html', [], $code = 404);
    }
}