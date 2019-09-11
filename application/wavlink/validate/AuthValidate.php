<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/1/20
 * Time: 16:04
 */

namespace app\wavlink\validate;


class AuthValidate extends BaseValidate
{
    protected $rule = [
        ['rules','require|max:80','配置规则必须输入|配置规则长度过长'],
        ['title','require|max:100','名称必须输入|名称过长'],
    ];
}