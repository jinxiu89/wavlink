<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/1/3
 * Time: 15:39
 */
namespace app\common\validate;
class UserInformation extends BaseValidate
{
    protected $rule = [
        ['first_name','require|max:64'],
        ['last_name','require|max:64'],
        ['email','require|email|max:32'],
        ['description','require|length:8,2048']
    ];
    protected $message = [
        'first_name.require'  => '{%first_name}',
        'first_name.max'      => '{%first_name_long}',

        'last_name.require'   => '{%last_name}',
        'last_name.nax'   => '{%last_name_long}',

        'email.require'       => '{%Email}',
        'email.email'         => '{%Email_error}',
        'email.max'           => '{%Email_error}',

        'description.require' => '{%description}',
        'description.length'  => '{%description_error}',
    ];
    protected $scene = [
      'add' => ['first_name','last_name'],
    ];
}