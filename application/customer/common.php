<?php
use \think\facade\Session;
use \think\facade\Request;
use think\facade\Config;
/**
 * @return mixed
 * 从浏览器得到语言
 */
/**
 * 根据数据库记录转换性别
 * @param $gender
 * @return string
 */
function getGender($gender){
    if($gender == 1){
        return 'Male';
    }elseif ($gender == 2){
        return 'Female';
    }
    return 'Secret';
}
