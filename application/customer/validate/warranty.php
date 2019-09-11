<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/9/27
 * Time: 16:07
 */
namespace app\customer\validate;


use think\Validate;

class warranty extends Validate
{

    protected $rule = [
        ['sn','require|max:15|unique:Warranty','{%SN is required}|{%SN is max 15 bit}|{%Has been registered}'],
    ];
    protected $scene = [
        'add'=>['sn']
    ];
}