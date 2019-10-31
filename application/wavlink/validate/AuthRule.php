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
        'id' => 'require|number',
        'status' => 'integer|in:-1,0,1',
    ];
    protected $message = [
        'id' => 'ID不合法（为空和不为数字）',
        'status' => '状态值不合法（整型，-1,0,1）'
    ];
    protected $scene = [
        'changeStatus' => ['id', 'status']
    ];
}