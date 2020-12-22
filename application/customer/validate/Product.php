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

/**
 * Class Product
 * @package app\customer\validate
 */
class Product extends Validate
{
    protected $rule=[
        'id'=>'require',
        'model'=>'require',
        'platform'=>'require',
        'country'=>'require',
        'create_time'=>'require',
    ];
    protected $message=[
        'model.require'=>'{%model is required}',
        'platform.require'=>'{%platform  is required}',
        'country'=>'{%country is require}',
        'create_time'=>'{%create time is require}',
    ];
    protected $scene=[
        'add'=>['model','platform','country','create_time'],
        'phone'=>['phone'],
        'register'=>['email'],
        'delete'=>['id']
    ];
}