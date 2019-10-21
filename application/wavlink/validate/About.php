<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/28
 * Time: 10:00
 *系统管理之系统设置 关于我们验证规则。
 */

namespace app\wavlink\validate;

/**
 * Class About
 * @package app\wavlink\validate
 */
class About extends BaseValidate
{
    /**验证规则**/
    protected $rule = [
        'id' => 'number',
        'name' => 'require|unique:about,name',
        'seo_title' => 'require',
        'keywords' => 'require|max:128',
        'description' => 'require|max:255',
        'status' => 'number|in:-1,0,1',
    ];
    protected $message = [
        'id' => 'ID不合法',
        'name.require' => '标题名称不能为空',
        'name.unique' => '标题名称不能重复',
        'seo_title.require' => 'seo标题不能为空',
        'keywords.require' => '关键词不能为空',
        'keywords.max' => '关键词太长，需控制在128个字符以内',
        'description.require' => '描述不能为空',
        'description.max' => '描述不能过长',
        'status.number' => '状态值不合法',
        'status.in' => '状态值不在合法范围内'
    ];
    /**场景设置**/
    protected $scene = [
        'add' => ['name', 'seo_title', 'keywords', 'description', 'status'],
        'edit' => ['id', 'name', 'seo_title', 'keywords', 'description', 'status'],
    ];
}
