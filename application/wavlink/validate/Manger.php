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
        ['id','number','id不合法'],
        ['username','require|unique:manger,username|max:50|alphaDash','登录名不能为空|已有该登录名|登录名过长|登录名不是字母或者数字'],
        ['name','require|max:50','用户名不能为空|用户名过长'],
        ['password','require|max:16','密码不能为空|密码长度过长'],
        ['mobile','max:11|number','号码错误|号码格式错误'],
        ['status','number|in:-1,0,1','状态必须是数字|状态范围不合法'],
        ['language','require','请选择操作网站']
    ];
    /**场景设置**/
    protected $scene = [
        'edit' => ['username','name','language']
    ];
}
