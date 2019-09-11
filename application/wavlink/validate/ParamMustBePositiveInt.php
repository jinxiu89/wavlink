<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/4/18
 * Time: 10:19
 */
namespace app\wavlink\validate;


class ParamMustBePositiveInt extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger',
        'language_id' => 'require|isPositiveInteger',
    ];
    protected $message = [
        'id' => 'id的参数错误',
        'language_id' => '语言参数错误',
    ];
}