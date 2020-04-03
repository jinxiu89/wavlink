<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/4/2 17:32
 * @User: admin
 * @Current File : Product.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\customer\validate;


use think\Validate;

class Product extends Validate
{
    protected $rule=[
        'model'=>'require',
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