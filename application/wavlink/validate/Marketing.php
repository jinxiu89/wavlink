<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/28
 * Time: 10:00
 *内容管理之 文章管理验证规则。
 */

namespace app\wavlink\validate;

use think\Validate;

class Marketing extends Validate
{
    /**验证规则**/
    protected $rule = [
        'id' => 'number',
        'name' => 'require|max:64',
        'url_title' => 'require|unique:marketing,url_title|max:128',
        'language_id' => 'require|number',
        'seo_title' => 'require|max:128',
        'keywords' => 'require|max:128',
        'description' => 'require|max:255',
        'status' => 'number|in:-1,0,1',
    ];
    protected $message=[
        'id.number'=>'id不合法',
        'name.require'=>'单页名称不能为空',
        'name.max'=>'单页名称过长',
        'url_title.require'=>'URL标题不能为空',
        'url_title.unique'=>'URL标题必须唯一',
        'url_title.max'=>'URL不能太长',
        'language_id.require'=>'语言ID不能为空',
        'language_id.number'=>'语言ID不合法',
        'seo_title.require'=>'SEO标题不能为空',
        'seo_title.max'=>'SEO标题不能太长',
        'keywords.require'=>'关键词不能为空',
        'keywords.max'=>'关键词不能太长',
        'description.require'=>'描述不能为空',
        'description.max'=>'描述不能太长',
        'status'=>'状态值不合法',
    ];
    /**场景设置**/
    protected $scene = [
        'add'=>['name','url_title','language_id','seo_title','keywords','description','status'],
        'edit'=>['id','name','url_title','language_id','seo_title','keywords','description','status'],
        'changeStatus'=>['id','status'],
        'del'=>['id']
    ];
}
