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
        ['id','number','id不合法'],
        ['name','require|unique:language,name|max:64','语言名称不能为空|语言名称过长'],
        ['code','require|unique:language,code|max:10|alphaDash','code标识不能为空|code标识必须唯一|code标识不能太长|code标识必须为字母和数字，下划线_及破折号-'],
        ['remark','max:64','说明太长了'],
        ['status','number|in:-1,0,1','状态必须是数字|状态范围不合法'],
    ];
    /**场景设置**/
    protected $scene = [

    ];
}
