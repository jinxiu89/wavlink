<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/28
 * Time: 10:00
 *服务分类之 分类管理验证规则。
 */

namespace app\wavlink\validate;
use think\Validate;

/**
 * Class ServiceCategory
 * @package app\wavlink\validate
 *
 */
class ServiceCategory extends BaseValidate
{
    /**验证规则**/
    protected $rule = [
        'id' => 'number',
        'language_id'=>'number',
        'name'=>'require|max:64',
        'url_title' => 'require|urlTitleIsOnly',
        'seo_title' => 'require|max:128',
        'keywords' => 'require|max:128',
        'description' => 'require|max:128',
        'status' => 'number|in:-1,0,1',
    ];
    protected $message = [
        'id.number' => '参数ID不合法',
        'language_id.number' => '语言ID不合法',
        'name.require'=>'分类名称不能为空',
        'name.max'=>'分类名称不能太长，需控制在64个字符以内',
        'url_title.require' => 'url 标题不能为空',
        'url_title.urlTitleIsOnly' => 'url 标题不能重复',
        'seo_title.require' => 'SEO标题不能为空',
        'seo_title.max' => 'SEO标题不能太长，需控制在128个字符以内',
        'keywords.require' => '关键词不能为空，这对网站的SEO造成严重伤害',
        'keywords.max' => '关键词不能超过128个字符',
        'description.require' => 'SEO描述不能为空，影响排名',
        'status.number' => '状态值必须是数字',
        'status.in' => '状态值不在范围内'
    ];
    /**场景设置**/
    protected $scene = [
        'add' => ['language_id', 'name','url_title', 'seo_title', 'keywords', 'description', 'status'],
        'edit' => ['id', 'language_id','name', 'url_title', 'seo_title', 'keywords', 'description', 'status'],
        'status' => ['id', 'status']
    ];
}