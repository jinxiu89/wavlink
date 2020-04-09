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
use app\common\model\customer\Product as Model;
use app\common\model\Category;
/**
 * Class product
 * @package app\common\service\customer
 */
class Product extends BaseService
{
    public function __construct()
    {
        $this->model=new Model();
    }

    /**
     * @return array
     */
    public function getCountry(){
        try{
            $data=(new Country())->field('country_id,name')->select();
            return $data->toArray();
        }catch (\Exception $exception){
            return [];
        }
    }
    public function getCategory($code){
        try{
            return (new Category())->getAllCategory($code);
        }catch (\Exception $exception){
            return [];
        }
    }

}