<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/28
 * Time: 10:00
 *服务管理之视频中心验证规则
 */
namespace app\wavlink\validate;

class Video extends BaseValidate
{
    /**验证规则**/
    protected $rule = [
        ['id','number','id不合法'],
        ['name','require|unique:video,name|max:128','视频名称不能为空|已有该视频名称|视频名称太长'],
        ['category_id','require|number|gt:0','视频分类不能为空|视频分类不合法'],
        ['language_id','require|number|gt:0','语言不能为空|语言不合法'],
    ];
    /**场景设置**/
    protected $scene = [
    ];
}
