<?php

namespace app\en_us\controller;

use app\common\helper\Category;
use app\common\model\Category as CategoryModel;
use app\common\model\Product as ProductModel;
use think\Exception;
use think\facade\Log;

/**
 * Class Product
 * @package app\en_us\controller
 */
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
        /*$system = config('system.system');
        if ($system['cache']) {
            $result = (new ProductModel())->binarySearchProduct($product, $this->code);
        } else {//产品详情页如果是搜索过来的数据 状态为禁用时也需要能展示

        }*/
        try{
            $result = ProductModel::where(['url_title' => $product])->find();
            $link = $result->links;
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
                $this->redirect(url('404'),[],404);
            }
        }catch (Exception $exception){
            Log::error($exception->getMessage());
            Log::close();
            $this->redirect(url('500'),[],500);
        }
    }
}