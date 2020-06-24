<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */

namespace app\common\model\Content;

use app\common\helper\Category as CategoryHelp;
use app\common\model\BaseModel;
use app\common\model\Content\Product as ProductModel;
use app\common\model\Language as LanguageModel;
use think\Collection;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\facade\Cache;
use think\facade\Config;
use think\facade\Log;

/**
 * Class Category
 * @package app\common\model
 *
 */
class Category extends BaseModel
{
    protected $hidden = ['create_time', 'update_time'];
    protected $table = 'category';//使用category表
    protected $model = 'category';

    //与产品多对多关联
    public function products()
    {
        return $this->belongsToMany('Product', 'product_category', 'product_id', 'category_id');
    }

    /**
     * @param $category
     * @param $language
     * @return array|void
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function getCategoryID($category, $language)
    {
        $category = self::where(['url_title' => $category, 'language_id' => $language])->find(); //这是查询的那一级分类
        $categoryID[] = $category->id;
        $map = [];
        $data['category'] = $category;
        $data['path'] = $category->path . $category->id;
        if ($category['level'] == 0) $map = ['language_id' => $language, 'status' => 1, 'level' => 1];
        if ($category['level'] == 1) $map = ['language_id' => $language, 'status' => 1, 'level' => 2];
        if ($category->is_parent == 1) { // 1 代表目录 0 代表子分类 目录的查出他的子目录
            $path = $category->path;
            $categorys = self::where('path', 'like', $path . $category->id . '%')
                ->where($map)->order(['listorder' => 'desc', 'id' => 'desc'])
                ->field('id,url_title,image,name')->select();
            $categoryID = array_merge($categoryID, array_column($categorys->toArray(), 'id'));
            $data['categoryID'] = $categoryID;
            $data['child'] = $categorys->toArray();
//            return ['category' => $category, 'categoryID' => $categoryID, 'path' => $category->path . $category->id, 'child' => $categorys->toArray()];
        } else {//本身为子目录的查出他同一个父级的子分类
            $categorys = self::where('path', 'like', $category->path)
                ->where($map)->order(['listorder' => 'desc', 'id' => 'desc'])
                ->field('id,url_title,image,name')->select();
            $data['categoryID'] = $categoryID;
            $data['child'] = $categorys->toArray();
        }
        return $data;
    }

    /**
     * @param $urlTitle
     * @param $code
     * @return array|\PDOStatement|string|\think\Model|null
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function getDetailsByUrlTitle($urlTitle, $code)
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $map = ['status' => 1, 'language_id' => $language_id, 'url_title' => $urlTitle];
        try {
            return self::where($map)->find();

        } catch (\Exception $exception) {
            return [];
        }
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
     * @throws DbException
     *
     */
    public static function getCategoryWithProduct($id)
    {
        $ids = static::getProductCategory($id);
        $order = ['listorder' => 'desc', 'id' => 'desc'];
        return (new ProductModel())->where('id', 'in', $ids)
            ->where('status', '=', 1)
            ->order($order)
            ->paginate('12', true);
    }

    /**
     * @param $categoryID
     */
    public static function getProductWithCategoryIds($categoryID)
    {
        $order = ['listorder' => 'desc', 'id' => 'desc'];
        return Db::table('product_category')->alias('category')->whereOr('category.category_id', 'in', $categoryID)
            ->join('product', ['product.id=category.product_id', 'product.status=1'])->order($order)
            ->field('id,image_litpic_url,name,model,listorder,create_time,language_id,url_title')->select();
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
        $data = ['parent_id' => $parentId, 'language_id' => $language_id];
        $order = ['listorder' => 'desc', 'id' => 'desc'];
        $model = 'category';
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
        return $this->where($data)->find();
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

    /**
     * @param $language_id
     * @return array|\PDOStatement|string|Collection
     * 修改缓存时间：2020.0604
     */
    public function getDataByLanguage($language_id)
    {
        //所有的程序都在try里运行 便于捕捉到所有的错误
        //如果开启debug的话就不走缓存取数据
        try {
            if (false == $this->debug) {
                $category = Cache::get($language_id . 'category');
                if ($category) return $category;
                $obj = $this->where(['status' => 1, 'language_id' => $language_id])
                    ->order(['listorder' => 'desc', 'id' => 'desc'])
                    ->field('name,url_title,id,parent_id,image')->select();
                Cache::set($language_id . 'category', $obj);
                return $obj;
            }
            return $this->where(['status' => 1, 'language_id' => $language_id])
                ->order(['listorder' => 'desc', 'id' => 'desc'])
                ->field('name,url_title,id,parent_id,image')->select();
        } catch (\Exception $exception) {
            if (true == $this->debug) Log::warning($exception->getMessage());
            return [];
        }
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
        return CategoryHelp::toLevel($category, '&nbsp;&nbsp;', 0);
    }

    //导航 直接循环出二级分类,对于二级分类

    /**
     * @param $code
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getChildsCategory($code)
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $map = ['status' => 1, 'language_id' => $language_id];
        $cate = self::field('id,parent_id,name')->all($map);
        return CategoryHelp::toLayer($cate, 'child');
    }
}