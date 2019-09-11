<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/1/20
 * Time: 16:04
 */

namespace app\wavlink\validate;


class InformationValidate extends BaseValidate
{
    protected $rule = [
        ['title','require|max:100','名称必须输入|名称过长'],
    ];
}