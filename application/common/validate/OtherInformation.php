<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/1/2
 * Time: 16:57
 */
namespace app\common\validate;

class OtherInformation extends BaseValidate {
    protected $rule =[
        ['purchasing_date','max:11'],
        ['purchasing_link','max:128']
    ];
    protected $message = [
        'purchasing_date.max' => '{%purchasing_date}',
        'purchasing_link.max' => '{%purchasing_link}',
    ];
}