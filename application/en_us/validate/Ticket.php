<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/1/2
 * Time: 17:13
 */

namespace app\en_us\validate;

use think\Validate;

/***
 * Class Ticket
 * @package app\en_us\validate
 */
class Ticket extends Validate
{
    protected $rule = [
        'first_name'=>'require|max:64|token',
        'last_name'=>'require|max:64',
        'email' => 'require|email',
        'model'=>'require|max:64',
        'sn'=>'require',
        'description'=>'require|length:16,255'
    ];
    protected $message = [
        'first_name.require' => '{%first name is require}',
        'first_name.max' => '{%first name is max to 64 character}',
        'first_name.token'=>'{%Cannot submit repeatedly}',
        'last_name.require' => '{%last name is require}',
        'last_name.max' => '{%last name is max to 64 character}',
        'model.require' => '{%model is require}',
        'model.max' => '{%model format is WL-WN***}',
        'sn.require' => '{%order number is require}',
        'description.require' => '{%description is require}',
        'description.max' => '{%description is min to 16 and max 255 character}'
    ];

    protected $scene = [
        'add' => ['first_name', 'last_name', 'email', 'model', 'sn', 'description']
    ];
}