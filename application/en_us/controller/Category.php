<?php

namespace app\en_us\controller;

use app\common\helper\Category as CategoryHelp;
use app\common\model\Content\Category as CategoryModel;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use app\common\service\en_us\Category as service;
use think\facade\Config;
use think\facade\Log;
use think\paginator\driver\Bootstrap;

/**
 * Class Category
 * @package app\en_us\controller
 */
class  Category extends Base
{

    public $service;

    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->service = new service();
    }

    /**
     * @param string $category
     * @return mixed
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     * @internal param string $name
     * @internal param $id
     */

    public function index($category = "")
    {
        if (empty($category) || !isset($category)) {
            abort(404);
        }
        $categoryModel = new CategoryModel();
        //获取当前的传入的url_title,获取分类id，得到分类的信息。
        $parent = CategoryModel::getDetailsByUrlTitle($category, $this->code);
        if ($parent->isEmpty()) abort(404);
//        //判断此分类下面是否有子分类
        $cate = $categoryModel->getAllCategory($this->code);
        $path = CategoryHelp::getParents($cate, $parent['id']);
        $hasChild = CategoryHelp::hasChild($cate, $parent['id']);
        $this->getChildsCateProduct($hasChild, $parent);
        return $this->fetch($this->template . '/category/index.html', [
            'parents' => $parent,
            'name' => $parent['name'],
            'url_title' => $category,
            'path' => $path,
            'current' => $category
        ]);
    }

    /**
     * @param string $category
     * @return mixed
     */
    public function category(string $category = "")
    {
        /*
         * 1 获得该分类和所有的子分类IDs
         * 2 查出产品分类在上述集合里的所有商品
         */
        try {
            $parent = $this->service->getCategoryIds($category, $this->language_id);
            if (empty($parent)) abort(404);
            $data = array_unique($this->service->getProductWithCategoryIds($parent['categoryID'],$category,$this->language_id), SORT_REGULAR);
            $count = count($data);
            $pages = input('page', 1);
            $size = 12;
            $page_options = ['var_page' => 'page', 'path' => $this->code . '/category/' . $category, 'query' => ['category' => $category]];//分页选项
            $page = Bootstrap::make($data, $size, $pages, $count, true, $page_options);
            $this->assign('parents', $parent['category']);
            $this->assign('url_title', $category);
            $this->assign('path', $parent['path']);
            $this->assign('current', $category);
            $this->assign('products', array_slice($data, ($pages - 1) * $size, $size));
            $this->assign('page', $page);
            $this->assign('child', $parent['child']);
            return $this->fetch($this->template . '/category/index.html');
        } catch (Exception $exception) {
            if (Config::get('app_debug', 'false')) {
                Log::error($exception->getMessage());
                print_r($exception->getMessage());
            }
            abort(500);
        }
    }

    private function getChildsCateProduct($hasChild, $parent)
    {
        $categoryModel = new CategoryModel();
        if ($hasChild) {
            //获取父分类下的所有产品
            $products = CategoryModel::getCategoryWithProduct($parent['id']);
//            dump($products);
            $parents = $parent; //导航条还是父分类
        } else {
            //没有子分类
            //获取此分类下的产品
            $products = CategoryModel::getCategoryWithProduct($parent['id']);
            //得到父分类
            $parents = $categoryModel->getCategoryById($this->code, $parent['parent_id']);
        }
        exit;
        //获取子分类
        $child = $categoryModel->getNormalCategory($this->code, $parents['id']);
        $page = $products->render();
        $this->assign('child', $child);
        $this->assign('products', $products);
        $this->assign('page', $page);
    }
}