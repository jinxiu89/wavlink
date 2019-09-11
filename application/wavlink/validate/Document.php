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
        ['id','number','id不合法'],
        ['name','require|unique:document,name|max:128','文档名称不能为空|已经添加该文档名称|文档名称太长'],
        ['title','require|max:128','文档标题不能为空|文档标题不能太长'],
        ['seo_title','require|max:128','SEO标题不能为空|SEO标题不能太长'],
        ['status','number|in:-1,0,1','状态必须是数字|状态范围不合法'],
    ];
    /**场景设置**/
    protected $scene = [

    ];
}
