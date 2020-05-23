<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/5/20 16:13
 * @User: admin
 * @Current File : driverCategory.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\wavlink\validate\service;


use app\wavlink\validate\BaseValidate;

/**
 * Class driverCategory
 * @package app\wavlink\validate\service
 */
class driverCategory extends BaseValidate
{
    protected $rule = [
        'id' => 'integer',
        'name' => 'require|alphaDash|unique:tb_drivers_category,name',
        'url_title' => 'require|unique:tb_drivers_category,url_title',
        'status'=>'integer|in:-1,0,1',

    ];
    protected $message = [
        'id' => 'ID不合法',
        'name.require' => '分类名必须填',
        'name.alphaDash' => '分类名不能重复',
        'status'=>'状态值不在合法范围内'
    ];
    protected $scene = [
        'add'=>['name'],
        'edit'=>['id'],
        'changeStatus'=>['id','status']
    ];
}