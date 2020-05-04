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
    protected $rule = [
        'id' => 'integer',
        'username' => 'require|alphaDash|unique:tb_user,username',
        'email' => 'require|email|unique:tb_user,email',
        'first_name' => 'require|max:20',
        'gender' => 'require|in:1,2,3',
        'birthday' => 'require|after:1940-1-1',
        'phone' => ['require', 'unique:tb_user', 'mobile'],
        'password' => ['regex' => '^(?![0-9]+$)(?![a-z]+$)(?![A-Z]+$)(?!([^(0-9a-zA-Z)])+$).{6,20}$'],
        'country' => 'require|max:20',
        'zip_code' => 'require|max:20',
        'billing_address' => 'max:120',
        'delivery_address' => 'max:120',
        'captcha' => 'require',
    ];
    protected $message = [
        'id' => 'ID illegal',
        'username.require' => '{%username is required}',
        'username.alphaDash' => '{%username is required}',
        'username.unique' => '{%This username has been used}',
        'email.require' => '{%email is required}',
        'email.email' => '{%email format Error}',
        'email.unique' => '{%This e-mail has been used}',
        'first_name.require' => '{%first name is required}',
        'first_name.max' => '{%first name is max 20}',
        'gender.require' => '{%gender is require}',
        'gender.integer' => '{%gender is error}',
        'birthday' => '{%birthday is required}',
        'birthday.after' => '{%Do not select a date before 1940.}',
        'phone' => '{%phone number is require}',
        'phone.unique' => '{%This phone number has been used}',
        'phone.mobile' => '{%Mobile format Error}',
        'password' => '{%password is to easy}',
        'country.require' => '{%Country is required}',
        'country.max' => '{%Country is mast be to 20 letter}',
        'zip_code' => '{%Zip code is required}',
        'billing_address' => '{%billing address too long}',
        'delivery_address' => '{%delivery address too long}',
        'captcha' => '{%Verification Code is require}'
    ];
    protected $scene = [
        'email' => ['password'],
        'phone' => ['password'],
        'registerEmail' => ['email','username'],
        'registerPhone' => ['phone','username'],
        'change_password' => ['id', 'password'],
        'changeName' => ['id', 'first_name'],
        'changeGender' => ['id', 'gender'],
        'changeCountry' => ['id', 'country'],
        'changeBirth' => ['id'],
        'changePhone' => ['id', 'phone', 'captcha'],
        'changeEmail' => ['id', 'email', 'captcha'],
        'changeCode' => ['id', 'zip_code'],
        'changeBillAddress' => ['id', 'billing_address'],
        'changeDeliveryAddress' => ['id', 'delivery_address'],
    ];
}