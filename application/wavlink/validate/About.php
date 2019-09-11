<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/28
 * Time: 10:00
 *系统管理之系统设置 关于我们验证规则。
 */
namespace app\wavlink\validate;


class About extends BaseValidate
{
    /**验证规则**/
    protected $rule = [
        ['id','number','id不合法'],
        ['name','require|unique:about,name','名称不能为空|已经添加该名称'],
        ['seo_title','require','SEO标题不能为空'],
        ['keywords','require|max:128','关键词不能为空|关键词过长'],
        ['description','require|max:255','描述不能为空|描述过长'],
        ['status','number|in:-1,0,1','状态必须是数字|状态范围不合法'],
    ];
    /**场景设置**/
    protected $scene = [

    ];
}
