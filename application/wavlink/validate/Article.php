<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/28
 * Time: 10:00
 *内容管理之 文章管理验证规则。
 */
namespace app\wavlink\validate;

class Article extends BaseValidate
{
    /**验证规则**/
    protected $rule = [
        'id'=>'number',
        'title'=>'require',
        'category_id'=>'require|number',
        'language_id'=>'require|number',
        'seo_title'=>'require|max:128',
        'seo_keys'=>'require|max:128',
        'seo_description'=>'require|max:128',
        'author'=>'max:64',
        'status'=>'number|in:-1,0,1',
    ];
    protected $message=[
        'id.number'=>'ID不合法！',
        'title.require'=>'标题不能为空!',
        'category_id.require'=>'分类ID不能为空！',
        'category_id.number'=>'分类ID不合法！',
        'language_id.require'=>'语言ID不能为空！',
        'language_id.number'=>'语言ID不合法！',
        'seo_title.require'=>'SEO标题不能为空！',
        'seo_title.max'=>'SEO标题不能太长，需控制在128个字符以内',
        'seo_keys.require'=>'关键词不能为空！',
        'seo_keys.max'=>'关键词不能太长,需控制在128个字符以内',
        'seo_description.require'=>'描述不能为空！',
        'seo_description.max'=>'描述不能太长，需控制在128个字符以内',
        'author.max'=>'作者名字不能太长',
        'status.number'=>'状态值不能为空！',
        'status.in'=>'状态值范围不合法！'

    ];
    /**场景设置**/
    protected $scene = [
        'add'=>['title','category_id','language_id','seo_title','seo_keys','seo_description','author','status'],
        'edit'=>['id','title','category_id','language_id','seo_title','seo_keys','seo_description','author','status']
    ];
}
