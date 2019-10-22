<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/28
 * Time: 10:00
 *内容管理之 推荐位管理验证规则。
 */

namespace app\wavlink\validate;

class Featured extends BaseValidate
{
    /**验证规则**/
    protected $rule = [
        'id' => 'number',
        'name' => 'require|unique:featured,name|max:128',
        'code' => 'require|unique:featured,code|max:32|alphaDash',
        'description' => 'require|max:128',
        'status' => 'number|in:-1,0,1',
    ];
    protected $message = [
        'id.number' => 'ID不合法',
        'name.require' => '标题名称不能为空',
        'name.unique' => '标题名称不能重复',
        'name.max' => '标题名称不能超过128个字符',
        'code.require' => '标识不能为空',
        'code.unique' => '标识不能重复',
        'code.max' => '标识不能超过32个字符',
        'code.alphaDash' => '标识不能为非字母',
        'description.require' => '描述不能为空',
        'description.max' => '描述不能超过128个字符',
        'status' => '状态值不在合法范围内',
    ];
    /**场景设置**/
    protected $scene = [
        'add' => ['name', 'code', 'description', 'status'],
        'edit' => ['id', 'name', 'code', 'description', 'status'],
    ];
}
