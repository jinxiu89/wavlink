<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/5/27
 * Time: 15:06
 */
namespace app\wavlink\validate;


class System extends BaseValidate
{
    protected $rule = [
        'page' => 'require|number|gt:0',
        'cache'=> 'in:0,1'
    ];
    protected $message = [
        'page' => '分页必须是正整数',
        'cache' => '缓存配置参数错误'
    ];
}