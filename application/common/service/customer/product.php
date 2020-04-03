<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/4/2 17:23
 * @User: admin
 * @Current File : product.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\common\service\customer;
use app\common\model\customer\Product as Model;
/**
 * Class product
 * @package app\common\service\customer
 */
class product extends BaseService
{
    public function __construct()
    {
        $this->model=new Model();
    }

}