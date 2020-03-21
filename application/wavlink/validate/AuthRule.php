<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/1/20
 * Time: 16:04
 */

namespace app\wavlink\validate;

/**
 * Class AuthGroup
 * @package app\wavlink\validate
 */
class AuthRule extends BaseValidate
{
    protected $rule = [
        'id' => 'require|integer',
        'title' => 'require|max:20',
        'name' => 'require|max:64',

//        'type' => 'require|integer',
        'status' => 'integer|in:-1,0,1',
    ];
    protected $message = [
        'id' => 'ID不合法（为空和不为数字）',
        'title'=>'权限名称不能为空或者不能够超过64个字符',
        'name'=>'权限地址不能为空或者不能够超过64个字符',
//        'title'=>'权限不能为空或者不能够超过64个字符',
        'status' => '状态值不合法（整型，-1,0,1）'
    ];
    protected $scene = [
        'add' => ['name','title','status'],
        'edit' => ['id','name','title','status'],
        'changeStatus' => ['id', 'status']
    ];
}