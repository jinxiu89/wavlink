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


class Model extends BaseValidate
{
    protected $rule = [
        ['code','require|max:4|unique:model','请输入code|code只能是三位|code不能重复'],
        ['cate','require','请选择产品分类'],
        ['description','require','请输入描述'],
        ['model','require|unique:model|max:10|alphaDash','Model必须输入|model和code不能重复|最多能输入10个字符|必须是字母数字，可以用中横杠']
    ];
    protected $scene = [
        'add'=>['code','cate','description','model'],
    ];
}