<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/28
 * Time: 10:00
 *内容管理之 分类管理验证规则。
 */

namespace app\wavlink\validate;
/**
 * Class Category
 * @package app\wavlink\validate
 *
 */
class Category extends BaseValidate
{
    /**验证规则**/
    protected $rule = [
        'id' => 'number',
        'name' => 'require|max:50',
        'seo_title' => 'require|max:128',
        'keywords' => 'require|max:128',
        'description' => 'require|max:128',
        'status' => 'integer|in:-1,0,1'
    ];
    protected $message = [
        'id' => 'ID不合法',
        'name.require' => '分类名不能为空',
        'name.max' => '分类名不能超过50个字符',
        'seo_title.require' => 'seo标题不能为空',
        'seo_title.max' => 'seo标题不能超过128个字符',
        'keywords.require' => '关键词不能为空',
        'keywords.max' => '关键词不能超过128个字符',
        'description.require' => '描述不能为空',
        'description.max' => '描述不能超过128个字符',
        'status' => '状态值不在合法范围内',
    ];
    /**场景设置**/
    protected $scene = [
        'add' => ['name', 'seo_title', 'keywords', 'description', 'status'],
        'edit' => ['id', 'name', 'seo_title', 'keywords', 'description', 'status'],
        'changeStatus'=>['id','status'],
        'listorder' => ['id', 'listorder'],
        'del'=>['id']
    ];
}
