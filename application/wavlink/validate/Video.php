<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/28
 * Time: 10:00
 *服务管理之视频中心验证规则
 */

namespace app\wavlink\validate;

class Video extends BaseValidate
{

    /**验证规则**/
    protected $rule = [
        'id' => 'number',
        'name' => 'require|unique:video,name|max:128',
        'category_id' => 'require|number|gt:0',
        'language_id' => 'require|number|gt:0',
    ];
    protected $message = [
        'id.number' => 'ID不合法',
        'name.require' => '名称不能为空',
        'name.unique' => '该名称已存在',
        'name.max' => '名称太长，需控制在128个字符以内',
        'category_id.require' => '分类ID不能为空',
        'category_id.number' => '分类ID不合法',
        'category_id.gt' => '分类ID必须是正整数',
        'language_id.require' => '语言ID不能为空',
        'language_id.number' => '语言ID不合法',
        'language_id.gt' => '语言ID必须是正整数',
    ];
    /**场景设置**/
    protected $scene = [
        'add' => ['name', 'category_id', 'language_id'],
        'edit' => ['id', 'name', 'category_id', 'language_id'],
    ];
}
