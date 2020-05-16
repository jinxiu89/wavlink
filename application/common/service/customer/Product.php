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

use app\common\model\Country;
use app\common\model\Customer\Product as Model;
use app\common\model\Category;

/**
 * Class product
 * @package app\common\service\customer
 */
class Product extends BaseService
{
    public function __construct()
    {
        $this->model = new Model();
    }

    /**
     * @param $code
     * @return array
     * 小方：银杏叶10g，沙棘10g，红曲10g,桑葚10g，槐米6g，决明子6g。高血脂小方
     * 对于多年加班且容易累的人，可以使用这个小方调理，使用方法是，自己去要点按剂量抓取，上述是一天的剂量，泡茶喝，每喝15天歇几天再喝
     *
     */
    public function getCategory($code)
    {
        try {
            return (new Category())->getAllCategory($code);
        } catch (\Exception $exception) {
            return [];
        }
    }

    public function getProductByUid($id)
    {
        try {
            return $this->model::getProductByUid($id);
        } catch (\Exception $exception) {
            return [];
        }
    }

}