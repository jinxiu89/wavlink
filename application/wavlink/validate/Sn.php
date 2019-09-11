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


class Sn extends BaseValidate
{
    protected $rule = [
        ['prefix','require|max:4','请输入prefix|prefix只能是四位,详细请见年周对照表'],
        ['erp_no','require|max:12|alphaNum','必须输入,切记，这是公司对外服务的号码，如果出错，将会影响公司信誉|最多能输入4个字符|必须是字母数字,不能有符号'],
        ['count','require|number','必须输入，切记是数字|你输入的不是数字诶！'],
        ['spec','require','必须填，判断是国内还是国外版本，售后时有需要']
    ];
    protected $scene = [
        'add'=>['prefix','erp_no','count','spec'],
        'edit'=>['id','prefix','erp_no','count','spec']
    ];
}