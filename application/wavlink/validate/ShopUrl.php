<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/1/8 17:03
 * @User: admin
 * @Current File : ShopUrl.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言：
 **/

namespace app\wavlink\validate;


/**
 * Class ShopUrl
 * @package app\wavlink\validate
 */
class ShopUrl extends BaseValidate
{
    protected $rule = [
        'id' => 'number|integer',
        'product_id' => 'require|integer',
        'name' => 'require|max:255',
        'url' => 'require|unique:product,model|max:128',
        'price' => 'require|max:16',
        'status' => 'integer|in:-1,0,1',
    ];
    protected $message = [
        'id.number' => 'id不合法',
        'id.integer' => 'ID必须int型数字',
        'product_id.require' => '必须要有产品ID',
        'product_id.integer' => '产品ID必须是整型数字',
        'name.require' => '销售站点名称不能为空',
        'name.max' => '销售站点名称销售站点名称太长',
        'price.require' => '建议价格不能为空',
        'price.max' => '建议价格不能太长',
        'status.integer' => '状态必须是数字',
        'status.in' => '状态范围不合法',
    ];
    protected $scene = [
        'add' => ['product_id', 'name', 'url', 'price'],
        'edit' => ['id', 'product_id', 'name', 'url', 'price'],
        'changeStatus' => ['id', 'status']
    ];
}