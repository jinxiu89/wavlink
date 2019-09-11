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


class Soft extends BaseValidate
{
    protected $rule = [
        ['ver','require|max:32|unique:Soft','请输入ver信息|ver最长是32位|新增的数据还不能和数据库里已有的数据重复呢！'],
        ['description','require|max:128','请输入描述信息|最长不能超过128个字符'],
        ['model_id','require','请选择支持的硬件，至少一个']
    ];
    protected $scene = [
        'saveID'=>['ver','description'],
        'add'=>['ver','description'],
        'save'=>['ver','model_id']
    ];
}