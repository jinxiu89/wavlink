<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/3/26 16:20
 * @User: admin
 * @Current File : User.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\customer\validate;
use think\Validate;

/**
 * Class User
 * @package app\customer\validate
 *
 */
class User extends Validate
{
    protected $rule=[
        'email'=>'require|email',
        'phone'=>'require'
    ];
    protected $message=[
        'email.require'=>'{%email is required}',
        'email.email'=>'{%email format is Error}',
        'phone'=>'{%phone number is require}'
    ];
    protected $scene=[
        'email'=>['email'],
        'phone'=>['phone'],
        'register'=>['email']
    ];
}