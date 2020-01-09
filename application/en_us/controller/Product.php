<?php

namespace app\en_us\controller;

use app\common\helper\Category;
use app\common\model\Category as CategoryModel;
use app\common\model\Product as ProductModel;

class Product extends Base
{

    public function index()
    {
        abort(404);
    }

    public function details($product = '')
    {
        if (!isset($product) || empty($product)) {
            abort(404);
        }
        $system = config('system.system');
        if ($system['cache']) {
            $result = (new ProductModel())->binarySearchProduct($product, $this->code);
        } else {
//            $result = ProductModel::getDetailsByUrlTitle($product, $this->code);
            $result = ProductModel::where(['url_title' => $product, 'status' => 1])->find();
        }
        $link = $result->links;
        print_r($link);
        if (!empty($result)) {
            if (!empty($result['album'])) {
                //产品详情页放大镜的图
                $albums = explode(',', $result['album']);
                $this->assign('albums', $albums);
                $this->assign('album', $albums[0]);
            }
            $categoryModel = new CategoryModel();
            $cate = $categoryModel->getAllCategory($this->code);
            //产品详情页路径导航
            $path = Category::getParents($cate, $result['category_id']);
            //查询是否有驱动
            $pDrivers = (new ProductModel())->selectProDriver($result, $this->code);
            return $this->fetch($this->template . '/product/details.html', [
                'result' => $result,
                'path' => $path,
                'pDrivers' => $pDrivers,
                'link' => $link
            ]);
        } else {
            abort(404);
        }
    }
}