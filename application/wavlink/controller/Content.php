<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:46
 */
namespace app\wavlink\controller;
use think\Controller;
use think\Request;

Class Content extends BaseAdmin {
    public function index()
    {
        return $this->fetch();
    }
    public function article_list(){
        return $this->fetch();
    }
}