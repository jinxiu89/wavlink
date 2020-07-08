<?php


namespace app\en_us\controller;


use phpDocumentor\Reflection\Types\This;
use think\Controller;

/**
 * Class Tuya
 * @package app\en_us\controller
 */
class Tuya extends Controller
{
    /**
     * @return mixed
     * 涂鸦app 下载地址映射
     */
    public function index(){
        return $this->fetch();
    }
    public function houz(){
        return $this->fetch();
    }
}