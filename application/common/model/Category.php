<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */

namespace app\common\model;

use app\common\model\Language as LanguageModel;
use app\common\helper\Category as CategoryHelp;
use app\common\model\Product as ProductModel;
use think\Db;
use think\Exception;

/**
 * Class Category
 * @package app\common\model
 *
 */
Class Category extends BaseModel
{
    protected $hidden = ['create_time', 'update_time'];
    protected $table = 'category';//使用category表
    protected $model = 'category';

    //与产品多对多关联
    public function products()
    {
        return $this->belongsToMany('Product', 'product_category', 'product_id', 'category_id');
    }

    //获取中间表数据,得到分类下的产品id
    public static function getProductCategory($id)
    {
        $product = self::get($id);
        $cates = $product->products;

        $ids = [];
        foreach ($cates as $v) {
            $ids[] = $v['id'];
        }
        return $ids;
    }

    /**
     * @param $id
     * 在删除分类时，需要注意到两点
     * 1、该分类下有子分类的不能删除或者改变状态
     * 2、该分类下有产品时不能删除或者改变状态
     * @return bool|string
     * 该项操作为了检查该条目能不能被改变状态
     */
    public function checkData($id)
    {
        try {
            $product = Db::table('product_category')->alias('category')
                ->where('category.category_id', '=', $id)
                ->join('product', 'product.id=category.product_id')
                ->select();
            if (!empty($product)) {
                return "该分类下有产品，无法执行该操作";
            }
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
        try {
            $child = Db::table('category')->where('parent_id', '=', $id)->select();
            if (!empty($child)) {
                return "有子分类，无法执行该操作";
            }
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
        return true;
    }

    /**
     * @param $id
     * @return \think\Paginator
     * @throws \think\exception\DbException
     *
     */
    public static function getCategoryWithProduct($id)
    {
        $ids = static::getProductCategory($id);
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        $product = (new ProductModel())->where('id', 'in', $ids)
            ->where('status', '=', 1)
            ->order($order)
            ->paginate();
        return $product;
    }

    //获取分类id 或者 子分类id
    public function getChildsIDByID($id, $code)
    {
        //传入一个分类id,还有语言id
        //判断该分类是否有子分类
        //如果没有 就返回这个id
        //如果有就 查出它的所有子类的id
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $cate = $this->getAllCategory($language_id);
        $HasChild = CategoryHelp::hasChild($cate, $id);
        if ($HasChild) {
            $ChildId = CategoryHelp::getChildsId($cate, $id);
            $str = implode(',', $ChildId);
        } else {
            $str = $id;
        }
        return $str;
    }


    //后台获取栏目列表
    public function getCategory($parentId = 0, $language_id)
    {
        $data = [
            'parent_id' => $parentId,
            'language_id' => $language_id
        ];
        $order = [
            'status' => 'desc',
            'listorder' => 'desc',
            'id' => 'asc',
        ];
        $model = 'Category';

        return Search($model, $data, $order);
    }

    //根据产品的 category_id，语言 获取分类数据
    public function getCategoryById($code, $id)
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $data = [
            'status' => 1,
            'id' => $id,
            'language_id' => $language_id
        ];
        $category = $this->where($data)->find();
        return $category;
    }


    //根据parent_id 获取分类数据
    public function getNormalCategory($code, $parent_id = 0)
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $map = [
            'status' => 1,
            'language_id' => $language_id,
            'parent_id' => $parent_id,
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'asc'
        ];
        return $this->where($map)
            ->order($order)
            ->field('name,url_title,id,image')
            ->select();
    }

    //获取所有的分类，并且递归
    public function getAllCategory($value)
    {
        $language_id = LanguageModel::getLanguageCodeOrID($value);
        $data_language = [
            'status' => 1,
            'language_id' => $language_id
        ];
        $data = [
            'status' => 1
        ];
        if (empty($language_id)) {
            $category = Category::all($data);
        } else {
            $category = Category::all($data_language);
        }
        $categorys = CategoryHelp::toLevel($category, '--', 0);
        return $categorys;
    }

    //导航 直接循环出二级分类,对于二级分类
    public function getChildsCategory($code)
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $map = [
            'status' => 1,
            'language_id' => $language_id
        ];
        $cate = self::all($map);
        $data = CategoryHelp::toLayer($cate, 'child');
//        $data = $this->getNormalCategory($language_id);
//        foreach ($data as $v) {
//            foreach ($cate as $vo) {
//                if($vo['parent_id'] == $v['id']) {
//                    $v['childs'] = CategoryHelp::getChilds($cate,$v['id']);
//                }
//            }
//        }
        return $data;
    }
}