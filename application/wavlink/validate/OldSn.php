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


class OldSn extends BaseValidate
{
    protected $rule = [
        ['prefix','require|max:12|unique:OldSn|alphaNum','请输入prefix|prefix只能是十二位,详细请见ERP订单号前十一位|不能和数据库里的数据条目重复|不能包含符号'],
        ['count','require|number','必须输入，切记是数字|你输入的不是数字诶！'],
        ['ver','require','必须输入固件版本，方便售后人员对此进行判断，以软件部的固件版本号为基准'],
        ['spec','require','必须填，判断是国内还是国外版本，售后时有需要']
    ];
    protected $scene = [
        'add'=>['prefix','count','ver','spec'],
        'edit'=>['id','prefix','count','ver','spec']
    ];
}