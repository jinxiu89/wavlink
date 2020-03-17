<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:37
 */
namespace app\wavlink\controller;

/**
 * Class Index
 * @package app\wavlink\controller
 */
Class Index extends BaseAdmin
{
    /**
     * @return mixed
     */
    public function index(){
        return $this->fetch();
    }
}