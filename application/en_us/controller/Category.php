<?php
namespace app\en_us\controller;

use app\common\helper\Category as CategoryHelp;
use app\common\model\Content\Category as CategoryModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

/**
 * Class Category
 * @package app\en_us\controller
 */
class  Category extends Base
{

    /**
     * @param string $category
     * @return mixed
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     * @internal param string $name
     * @internal param $id
     */

    public function index($category = "") {
        if (empty($category) || !isset($category)) {
            abort(404);
        }
        $categoryModel = new CategoryModel();
        //获取当前的传入的url_title,获取分类id，得到分类的信息。
        $parent = CategoryModel::getDetailsByUrlTitle($category, $this->code);

        if (empty($parent)) {
            abort(404);
        }
//        //判断此分类下面是否有子分类
        $cate = $categoryModel->getAllCategory($this->code);
        $path = CategoryHelp::getParents($cate, $parent['id']);
        $hasChild = CategoryHelp::hasChild($cate, $parent['id']);
        $this->getChildsCateProduct($hasChild, $parent);
        return $this->fetch($this->template.'/category/index.html', [
            'parents'  => $parent,
            'name' => $parent['name'],
            'url_title' => $category,
            'path' => $path,
            'current'=>$category
        ]);
    }

    private function getChildsCateProduct($hasChild, $parent) {
        $categoryModel = new CategoryModel();
        if ($hasChild){
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
        //获取子分类
        $child = $categoryModel->getNormalCategory($this->code, $parents['id']);
        $page=$products->render();
        $this->assign('child', $child);
        $this->assign('products', $products);
        $this->assign('page', $page);
    }
}