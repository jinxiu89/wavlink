<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/28
 * Time: 10:00
 */
namespace app\wavlink\validate;
use think\Validate;

class Admin extends Validate
{
    /**验证规则**/
    protected $rule = [
        ['name', 'require|max:5000', '名称必须输入|名称不能超过500个字符'],
        ['code', 'require|max:200', '语言名必须输入|名称不能超过20个字符'],
        ['title','require|max:120', '标题必须输入|不能超过50个字'],
        ['id','number'],
        ['remark', 'max:1000', '不能超过1000个字符'],
        ['status','number|in:-1,0,1','状态必须是数字|状态范围不合法'],
        //管理员的
        ['password', 'require|alphaDash|max:16', '密码必须输入|请输入正确的密码|长度不能超过16个字符'],
        ['mobile','number|length:11','号码格式错误|号码格式错误'],
        ['email','email','请输入正确的邮箱地址'],
        //图片
        ['url','url','url错误'],
        //下载验证规则
        ['urlfirst','url','下载地址错误'],
        ['urlsecond','url','下载地址错误'],
        //视频中心规则
        ['urlchina','url','国内链接不合法'],
        ['urlabroad','url','国外链接不合法'],
        //FAQ
        ['sort','number','排序必须是数字']
    ];
    /**场景设置**/
    protected $scene = [
        'system_language_add'  => ['name','code','remark','id'],//添加
        'category_add'         => ['name','id'],//分类添加场景
        'productAdd'           => ['name','title'],//产品添加场景
        'manger_add'           => ['name','password','mobile','email'], //添加管理员
        'status'               => ['id','status'],//状态场景
        'article_add'          => ['id','title'],//文章添加场景
        'images_add'           => ['title','url'],
        'featured_add'         => ['name'],//推荐位添加场景
        'document_add'         => ['name','title','id'],//文档添加场景
        'drives_add'           => ['id'],//添加下载场景
        'video_add'            => ['name','id','status'],//视频添加场景
        'Faq_add'              => ['name','sort','status','id'],//Faq添加场景
        'setting_add'          => ['title'],//系统站点配置场景
        'about_add'            => ['id','status'] //关于我们添加场景
    ];
}
