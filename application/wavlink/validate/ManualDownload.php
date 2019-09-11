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


class ManualDownload extends BaseValidate
{
    protected $rule = [
        ['language','require|max:32','语言必须输入|语言最长32个字符'],
        ['url','require|unique:ManualDownload|url','下载url必须输入|下载链接不能重复|必须是url格式']
    ];
    protected $scene = [
        'add'=>['language','url'],
        'update'=>[''],
    ];
}