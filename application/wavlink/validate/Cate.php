<?php
/**
 * Created by PhpStorm.
 * User: web jinxiu89@163.com
 * Date: 2018/8/29
 * Time: 15:35
 * 命名规范：文件名首字母大写
 * 类 首写字母大写
 * 函数 驼峰命名  如getInfo
 */

namespace app\wavlink\validate;


class Cate extends BaseValidate
{
    protected $rule = [
        ['code','require|max:1|alpha|unique:Cate','请输入code|code只能是一位|只能是字母|code不能重复'],
    ];
    protected $scene = [
        'add'=>['code']
    ];
}