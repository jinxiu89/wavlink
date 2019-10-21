<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/28
 * Time: 10:00
 *系统管理之语言管理验证规则。
 */

namespace app\wavlink\validate;

use think\Validate;

class Language extends Validate
{
    /**验证规则**/
    protected $rule = [
        'id' => 'number',
        'name' => 'require|unique:language,name|max:64',
        'code' => 'require|unique:language,code|max:10|alphaDash',
        'remark' => 'max:64',
        'status' => 'number|in:-1,0,1',
    ];
    protected $message=[
        'id.number'=>'ID不合法',
        'name.require'=>'名称不能为空',
        'name.unique'=>'名称不能重复',
        'name.max'=>'名称过长',
        'code.require'=>'CODE不能为空',
        'code.unique'=>'CODE不能重复',
        'code.max'=>'CODE不能太长',
        'code.alphaDash'=>'CODE必须为字母和数字，下划线_及破折号-',
        'status'=>'状态值不合法或不在合法范围内',
    ];
    /**场景设置**/
    protected $scene = [
        'add'=>['name','code','remark','status'],
        'edit'=>['id','name','code','remark','status'],
    ];
}
