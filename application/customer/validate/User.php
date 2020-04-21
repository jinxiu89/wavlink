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
        'id'=>'integer',
        'email'=>'require|email',
        'first_name'=>'require|max:20',
        'gender'=>'require|in:1,2,3',
        'phone'=>'require',
        'password'=>['regex'=>'^(?![0-9]+$)(?![a-z]+$)(?![A-Z]+$)(?!([^(0-9a-zA-Z)])+$).{6,20}$'],
    ];
    protected $message=[
        'email.require'=>'{%email is required}',
        'email.email'=>'{%email format is Error}',
        'first_name.require'=>'{%first name is required}',
        'first_name.max'=>'{%first name is max 20}',
        'gender.require'=>'{%gender is require}',
        'gender.integer'=>'{%gender is error}',
        'phone'=>'{%phone number is require}',
        'password'=>'{%password is to easy }'
    ];
    protected $scene=[
        'email'=>['email','password'],
        'phone'=>['phone','password'],
        'register'=>['email'],
        'change_password'=>['id','password'],
        'changeName'=>['id','first_name'],
        'changeGender'=>['id','gender']
    ];
}