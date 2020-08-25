<?php

namespace app\en_us\controller;

use app\common\helper\Category;
use app\common\model\Content\Category as CategoryModel;
use app\common\model\Content\Product as ProductModel;
use think\Exception;
use think\facade\Config;
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

    /**
     * @param string $product
     * @return mixed
     */
    public function details($product = '')
    {
        try{
            $result = ProductModel::where(['url_title' => $product])->find();
            $link = $result->links;
            if (!$result->isEmpty()) {
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
                //todo::log
                $this->redirect(url('404'),[],404);
            }
        }catch (Exception $exception){
            print_r($exception->getMessage());exit;
            Log::error($exception->getMessage());
            Log::close();
            if(Config::get('app_debug')){
                print_r($exception->getMessage());
            }
            $this->redirect(url('500'),[],500);
        }
    }
}