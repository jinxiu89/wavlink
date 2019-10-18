<?php

/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/1/2
 * Time: 17:12
 */

namespace app\en_us\validate;

use think\Validate;

/***
 * Class Base
 * @package app\en_us\validate
 *
 */
class Base extends Validate
{

    protected $rule = [
        'email' => 'require|email',
    ];
    protected $message = [
        'email.require' => 'email必须填写',
        'email.email' => '请保证您的邮箱格式是正确的',
    ];
}