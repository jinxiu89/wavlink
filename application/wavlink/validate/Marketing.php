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
        ['id','number','id不合法'],
        ['name','require|max:64','单页名称不能为空|单页名称过长'],
        ['url_title','require|unique:marketing,url_title|max:128','URL标题不能为空|URL标题必须唯一|URL不能太长'],
        ['language_id','require|number','语言不能为空|语言不能为空'],
        ['seo_title','require|max:128','SEO标题不能为空|SEO标题不能太长'],
        ['keywords','require|max:128','关键词不能为空|关键词不能太长'],
        ['description','require|max:255','描述不能为空|描述不能太长'],
        ['status','number|in:-1,0,1','状态必须是数字|状态范围不合法'],
    ];
    /**场景设置**/
    protected $scene = [

    ];
}
