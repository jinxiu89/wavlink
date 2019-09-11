<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/5/25
 * Time: 16:46
 */
namespace app\wavlink\validate;
class ListorderValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger',
        'listorder' => 'require|isPositiveInteger',
    ];
    protected $message = [
        'id' => 'id的参数错误',
        'listorder' => '排序数字要正整数',
    ];
}