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


class Manual extends BaseValidate
{
    protected $rule = [
        ['model','require|max:32','请输入型号|型号最大只支持32个字符'],
        ['title','require|max:32','标题必须输入|标题最长32个字符'],
        ['url_title','require','url标题必须输入'],
    ];
    protected $scene = [
        'add'=>['model','title','url_title']
    ];
}