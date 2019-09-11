<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/1/3
 * Time: 15:46
 */
namespace app\common\validate;
class ProductInformation extends BaseValidate
{
    protected $regex = [
        'model'=>'WL\\-[A-Z][0-9A-Z]{0,10}|[A-Z][0-9A-Z]{0,15}',//首位为字母开头后面为数字加子母，最多为16个字符串
        'sn'   =>'(((((19|20)\d{2})(0?[13-9]|1[012])(0?[1-9]|[12]\d|30))|(((19|20)\d{2})(0?[13578]|1[02])31)|(((19|20)\d{2})0?2(0?[1-9]|1\d|2[0-8]))|((((19|20)([13579][26]|[2468][048]|0[48]))|(2000))0?229)))[A-Z]{2}\d{2,10}'
    ];
    protected $rule = [
        ['model','require|max:128|regex:model'],
        ['sn','require|max:64'],
    ];
    protected $message = [
        'model.require' => '{%model}',
        'model.max'     => '{%model_error}',
        'model.regex'   => '{%model_error}',
        'sn.require'    => '{%sn}',
        'sn.max'        => '{%sn_error}',
        'sn.regex'      => '{%sn_error}'
    ];
}