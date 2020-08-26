<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/28
 * Time: 10:00
 *服务管理之视频中心验证规则
 */
namespace app\wavlink\validate;

/**
 * Class Drivers
 * @package app\wavlink\validate
 * 修正验证消息以及场景验证
 */
class Drivers extends BaseValidate
{
    /**验证规则**/
    protected $rule = [
        'id'=>'number',
        'name'=>'require|max:128',
        'image_litpic_url'=>'max:500',
        'version_number'=>'max:32',
        'category_id'=>'require|number',
        'language_id'=>'require|number',
        'seo_title'=>'require|max:128',
        'keywords'=>'require|max:255',
        'urlfirst'=>'url|max:500',
        'urlsecond'=>'url|max:500',
        'running'=>'require|max:255',
        'listorder'=>'number|max:5',
        'descrip'=>'require|max:128',
        'status'=>'integer|in:-1,0,1',
    ];
    protected $message=[
        'id.number'=>'ID不合法！',
        'name.require'=>'驱动名称不能为空！',
        'name.unique'=>'驱动名称不能重复',
        'image_litpic_url.max'=>'图片地址不能够太长！',
        'size.max'=>'尺寸大小不能太长',
        'version_number.max'=>'版本号不能太长，请按照公司的版本规则命名',
        'category_id.require'=>'分类ID不能为空！',
        'category_id.number'=>'分类ID 不合法！',
        'language_id.require'=>'语言ID必须存在！',
        'language_id.number'=>'语言ID不合法！',
        'seo_title.require'=>'SEO标题不能为空！',
        'seo_title.max'=>'SEO标题不能太长，需控制在128个字符以下',
        'keywords.require'=>'关键词不能为空！',
        'keywords.max'=>'关键词不能太长，64个字符以下！',
        'urlfirst.url'=>'URL格式不对!',
        'urlfirst.max'=>'URL最长不能超过255个字符！',
        'urlsecond.url'=>'URL格式不对!',
        'urlsecond.max'=>'URL最长不能超过255个字符！',
        'running.require'=>'运行环境不能为空！',
        'running.max'=>'运行环境不能太长！',
        'listorder.number'=>'排序数值不合法！',
        'listorder.max'=>'排序数值不能超过五位数！',
        'descrip.require'=>'描述不能为空！',
        'descrip.max'=>'描述长度最长不能超过28个字符！',
        'status.integer'=>'状态值不合法！',
        'status.in'=>'状态值不在合法范围内！'
    ];
    /**场景设置**/
    protected $scene = [
        'add'=>['name','size','version_number','category_id','language_id','seo_title','running','listorder','descip','status'],
        'edit'=>['id','size','version_number','category_id','language_id','seo_title','running','listorder','descip','status'],
        'listorder'=>['id','listorder'],
        'changeStatus'=>['id','status']
    ];
}
