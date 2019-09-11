<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:37
 */
namespace app\wavlink\controller;

Class Service extends BaseAdmin
{
    public function index(){
        return $this->fetch();
    }
}