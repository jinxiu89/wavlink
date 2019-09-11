<?php

/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/1/2
 * Time: 17:12
 */
namespace app\en_us\validate;
use think\Exception;
use think\Request;
use think\Validate;

class Base extends Validate
{

    protected $rule =[
        ['email','require|email','必须填写|邮箱格式错误']
    ];

}