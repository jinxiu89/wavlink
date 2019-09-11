<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/28
 * Time: 10:00
 *服务管理之视频中心验证规则
 */
namespace app\wavlink\validate;

class Drivers extends BaseValidate
{
    /**验证规则**/
    protected $rule = [
        ['id', 'number', 'id不合法'],
        ['name', 'require|unique:video,name|max:128', '名称不能为空|已有该名称|名称太长'],
        ['image_litpic_url', 'max:500', '缩略图外链地址太长'],
        ['size', 'max:32', '文件大小字符过多'],
        ['version_number', 'max:32', '版本号字符过多'],
        ['category_id', 'require|number', '视频分类不能为空'],
        ['language_id', 'require|number', '语言不能为空'],
        ['seo_title', 'require|max:128', 'SEO标题不能为空|SEO标题不能太长'],
        ['keywords', 'require|max:128', '关键词不能为空|关键词不能太长'],
        ['urlfirst', 'url|max:500', '下载地址一格式错误|下载地址一不能太长'],
        ['urlsecond', 'url|max:500', '下载地址二格式错误|下载地址二不能太长'],
        ['running', 'require|max:255', '运行坏境不能为空|运行环境字符过多'],
        ['listorder', 'number|max:5', '排序必须为数字|排序不能太大'],
        ['descrip', 'require|max:128', '描述不能为空|描述不能太长'],
        ['status', 'number|in:-1,0,1', '状态必须是数字|状态范围不合法'],
    ];
    /**场景设置**/
    protected $scene = [

    ];
}
