<?php
namespace app\wavlink\validate;
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/3/29
 * Time: 11:09
 */
class Mark extends BaseValidate
{
    protected $rule =[
        ['listorder','number|max:5','排序必须是数字|输入太长了'],
        ['mark','number|in:-1,0,1','mark不合法|mark不合法'],
        ['type','number|in:1,2,3,4','type不合法|type不合法']
    ];
    protected $scene =[
        'listorder' => ['listorder'],
        'mark'      => ['mark'],
        'type'      => ['type']
    ];
}