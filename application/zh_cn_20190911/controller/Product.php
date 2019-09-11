<?php
namespace app\zh_cn\controller;
use \app\common\model\Category as CategoryModel;
use \app\common\model\Product as ProductModel;
use app\common\helper\Category;

class Product extends Base
{
    public function index(){
        abort(404);
    }
    public function details($product='')
    {
//        print_r($this->template);exit;
        if (!isset($product) || empty($product)) {
            abort(404);
        }
        $system = config('system.system');
        if ($system['cache']) {
            $result = (new ProductModel())->binarySearchProduct($product, $this->code);
        }else{
            $result = ProductModel::getDetailsByUrlTitle($product, $this->code);
        }
        if (!empty($result)) {
            if (!empty($result['album'])){
                //产品详情页放大镜的图
                $albums = explode(',',$result['album']);
                $this->assign('albums',$albums);
                $this->assign('album',$albums[0]);
            }
            $categoryModel = new CategoryModel();
            $cate = $categoryModel->getAllCategory($this->code);
            //产品详情页路径导航
            $path=Category::getParents($cate,$result['category_id']);
            //查询是否有驱动
            $pDrivers = (new ProductModel())->selectProDriver($result,$this->code);
            return $this->fetch($this->template.'/product/details.html', [
                'result' => $result,
                'path'   => $path,
                'pDrivers' => $pDrivers
            ]);
        }else {
            abort(404);
        }
    }
}