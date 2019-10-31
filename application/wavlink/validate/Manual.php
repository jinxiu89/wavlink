<?php
/**
 * Created by PhpStorm.
 * User: web jinxiu89@163.com
 * Date: 2018/8/29
 * Time: 15:35
 * 命名规范：文件名首字母大写
 * 类 首写字母大写
 * 函数 驼峰命名  如getInfo
 */

namespace app\wavlink\validate;


/**
 * Class Manual
 * @package app\wavlink\validate
 */
class Manual extends BaseValidate
{
    protected $rule = [
        'id' => 'require|number',
        'model' => 'require|max:32',
        'title' => 'require|max:32',
        'url_title' => 'require|urlTitleIsOnly',
        'status' => 'integer|in:-1,0,1'
    ];
    protected $message = [
        'id.require' => 'ID非法',
        'id.number' => 'ID不合法',
        'model.require' => '请输入型号',
        'model.max' => '型号最大只支持32个字符',
        'title.require' => '标题必须输入',
        'title.max' => '标题最长32个字符',
        'url_title.require' => 'url标题必须输入',
        'url_title.urlTitleIsOnly' => 'url标题不能重复',
        'status' => '状态值不合法'
    ];
    protected $scene = [
        'add' => ['model', 'title', 'url_title'],
        'edit' => ['id', 'model', 'title'],
        'changeStatus' => ['id', 'status']
    ];
}