<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/28
 * Time: 10:00
 *服务管理之文档中心验证规则
 */
namespace app\wavlink\validate;

class Document extends BaseValidate
{
    /**验证规则**/
    protected $rule = [
        'id'=>'number',
        'name'=>'require|unique:document,name|max:128',
        'title'=>'require|max:128',
        'seo_title'=>'require|max:128',
        'status'=>'number|in:-1,0,1'
    ];
    protected $message=[
        'id.number'=>'ID不合法！',
        'name.require'=>'文档名称不能为空！',
        'name.unique'=>'文档名称不能重复！',
        'title.require'=>'文档标题不能为空！',
        'title.max'=>'文档标题不能太长，需控制在128字符以内',
        'seo_title'=>'SEO标题不能为空!',
        'seo_title.max'=>'SEO标题不能太长，需控制在128字符以内',
        'status.number'=>'状态值不合法！',
        'status.in'=>'状态值超过有效范围！'
    ];
    /**场景设置**/
    protected $scene = [
        'add'=>['name','title','seo_title','seo_title','status'],
        'edit'=>['id','name','title','seo_title','seo_title','status']
    ];
}
