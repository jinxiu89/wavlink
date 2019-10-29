<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/28
 * Time: 10:00
 *系统管理之系统设置 管理员验证规则。
 */

namespace app\wavlink\validate;

use think\Validate;

class Manger extends Validate
{
    /**验证规则**/
    protected $rule = [
        'id' => 'number',
        'username' => 'require|unique:manger,username|max:50|alphaDash',
        'name' => 'require|max:50',
        'password' => 'require|max:16',
        'mobile' => 'max:11|number',
        'status' => 'integer|in:-1,0,1',
        'language' => 'require',
    ];
    protected $message=[
        'id'=>'ID不合法',
        'username.require'=>'用户名不能为空',
        'username.unique'=>'用户名不能重复',
        'username.max'=>'用户名不能超过50个字符',
        'username.alphaDash'=>'用户名必须是字母',
        'name.require'=>'名字不能为空',
        'name.max'=>'名字不能超过50个字符',
        'password.require'=>'密码不能为空',
        'password.max'=>'密码不能太长',
        'mobile'=>'手机号码不能超过11位数字',
        'status'=>'状态值不在合法范围内',
        'language'=>'语言不能为空',
    ];
    /**场景设置**/
    protected $scene = [
        'edit' => ['username', 'name', 'language'],
        'changeStatus'=>['id','status']
    ];
}
