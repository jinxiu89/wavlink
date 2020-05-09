<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */

namespace app\common\model\Content;

use app\common\helper\Algorithm as AlgorithmHelp;
use app\common\model\Category as CategoryModel;
use app\common\model\Language as LanguageModel;
use \app\common\model\Drivers as DriversModel;
use Exception;
use PDOStatement;
use think\Collection;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\facade\Cache;
use think\model\relation\HasMany;

/**
 * Class Product
 * @package app\common\model
 *
 */
Class Product extends BaseModel
{
    protected $table = 'product';

    //en_us模块下Category控制器使用到这个方法。获取该父分类下的所有产品。
    public function categorys()
    {
        return $this->belongsToMany('Category');
    }

    /**
     * @return HasMany
     * 产品销售连接：一个产品可以有多个连接
     */
    public function links(){
        return $this->hasMany('ShopLink','product_id','id')->field('id,name,url,price');
    }

    //获取中间表数据,得到产品所属分类id
    public static function getProductCategory($id)
    {
        $product = self::get($id);
        $cates = $product->categorys;
        $ids = [];
        foreach ($cates as $v) {
            $ids[] = $v['id'];
        }
        return $ids;
    }

    /**
     * @param $language
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function allData($language)
    {
        $data = self::where(['language_id' => $language, 'status' => 1])->field('id,name,url_title,model,seo_title,keywords,description,features,status,listorder')->select();
        return Collection::make($data)->toArray();
    }

    public static function getCategoryWithProduct($id)
    {
        $category = self::get($id);
        $cates = $category->categorys;
        return $cates;
    }

    /**
     * @param $category_id
     * @return mixed
     */
    public function getTopOrder($category_id)
    {
        $data = Db::table('product_category')->alias('category')->
        where('category.category_id', '=', $category_id)->
        join('product', 'product.id=category.product_id')->max('listorder');
        return $data;
    }

    public function mark($data)
    {
        try {
            if ($this->isUpdate(true)->allowField(true)->save($data, ['id' => $data['id']])) {
                return true;
            } else {
                return "保存数据失败";
            }
        } catch (Exception  $e) {
            return $e->getMessage();
        }
    }

    public function productSave($data)
    {
        if (!empty($data['id'])) {
            $product = self::get($data['id']);
            $this->allowField(true)->save($data, ['id' => $data['id']]);
            $roles = $product->categorys;
            $cates = [];
            foreach ($roles as $v) {
                $cates[] = $v['id'];
            }
            $product->categorys()->detach($cates);
            $res = $product->categorys()->attach($data['cates']);
        } else {
            $data['status'] = 1;
            $this->allowField(true)->save($data);
            //获取自增id
            $id = $this->id;
            $product = self::get($id);
            $this->allowField('listorder')
                ->isUpdate(true)
                ->save(
                    ['listorder' => $id + 100],
                    ['id' => $id]
                );
            $res = $product->categorys()->save($data['cates']);
        }
        return ($res !== true) ? true : false;
    }

    /**
     * @param $category_id
     * @return array|PDOStatement|string|Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getDataByCategory($category_id,$language_id)
    {
        //1、如果这个分类有子分类，查出所有的子分类ID
        //2、通过分类ID查出所有的产品ID
        $category = CategoryModel::get($category_id);
        if ($category['parent_id'] == 0) {
            $childCategory = CategoryModel::where(['parent_id' => $category_id])->select();
            $child_id = [];
            foreach ($childCategory as $child) {
                $child_id[] = $child['id'];
            }
            $child_id[] = $category['id'];
            return Db::table('product_category')->alias('category')->whereOr('category.category_id', 'in', $child_id)
                ->join('product', ['product.id=category.product_id', 'product.status=1', 'product.language_id='.$language_id])
                ->field('id,image_litpic_url,name,model,listorder,create_time,language_id')->select();
        } else {
            //todo::其他的操作
            return Db::table('product_category')->alias('category')->whereOr('category.category_id', '=', $category_id)
                ->join('product', ['product.id=category.product_id', 'product.status=1','product.language_id='.$language_id])
                ->field('id,image_litpic_url,name,model,listorder,create_time,language_id')
                ->select();
        }
    }

    /**
     * @param string $name
     * @param $language_id
     * @return mixed
     */
    public function getDataByName($name='',$language_id){
        $map['name|model|url_title|seo_title|keywords'] = ['like', '%' . $name . '%'];
        $map['status'] = 1;
        $map['language_id'] = $language_id;
        $order=['listorder'=>'desc','id'=>'desc'];
        try {
            $query = self::where('name|model|url_title|seo_title|keywords','like','%'.$name.'%')
                ->where(['status'=>1,'language_id'=>$language_id])
                ->field('id,image_litpic_url,name,model,listorder,create_time,language_id');
            $result['data'] = $query->order($order)->paginate();
            $result['count'] = $query->count();
            return $result;
        } catch (DbException $e) {
        }
    }

    /***
     * @param $name
     * @param $language_id
     * @return array|string
     */
    public function getSelectProduct($name, $language_id) {
        //多条件模糊查询
        $map['status'] = 1;
        $map['name|model|url_title|seo_title|keywords'] = ['like', '%' . $name . '%'];
        $map['language_id'] = $language_id;
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        return Search('Product', $map, $order);
    }

    //前端搜索当前模块下的语言的数据，en_us/zh_cn模块下search控制器用到这个方法
    public function frontendGetSelectProduct($key, $code)
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $model = 'product';
        $map['status'] = 1;
        $map['name|model|url_title|seo_title|keywords'] = array('like', '%' . $key . '%');
        $map['language_id'] = $language_id;
        $order = [
            'mark' => 'desc',
            'listorder' => 'desc'
        ];
        return Search($model, $map, $order);
    }

    //Ajax搜索 下拉联想列表
    public function opSelectProduct($key, $code, $limit)
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $map['status'] = 1;
        $map['name|model'] = array('like', '%' . $key . '%');
        $map['language_id'] = $language_id;
        $order = [
            'listorder' => 'desc',
            'create_time' => 'asc'
        ];
        return $this->where($map)
            ->order($order)
            ->limit($limit)
            ->field('name,model')
            ->select();
    }


    /**
     * @param $code
     * @param $list
     * @return false|PDOStatement|string|Collection /推荐的产品。取出排名最高的3个产品
     * 取出排序最高的产品
     * 数量为 list
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function getListProduct($code, $list)
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $map = [
            'status' => 1,
            'language_id' => $language_id
        ];
        $order = [
            'listorder' => 'desc'
        ];
        $products = self::where($map)
            ->limit($list)
            ->field('url_title,image_litpic_url,name,model')
            ->order($order)
            ->select();
        return $products;
    }

    /**
     * 查询产品的相关驱动地址
     * @param $result
     * $result 单独的一个产品的信息
     * @param $code
     * @return bool|mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function selectProDriver($result, $code)
    {
        //设置缓存
        if (Cache::get($result['id'] . $result['model'], '')) {
            $pDirver = Cache::get($result['id'] . $result['model']);
        } else {
            $fDirver = DriversModel::getSelectDrivers($result['model'], $code);
            Cache::set($result['id'] . $result['model'], $fDirver['data'], 7200);
            $pDirver = Cache::get($result['id'] . $result['model']);
        }
        if (!empty($pDirver)) {
            return $pDirver;
        } else {
            return false;
        }
    }

    //利用二分法和缓存去查找产品
    public function binarySearchProduct($url_title, $code)
    {
        if (Cache::get('allProductByCode' . $code, '')) {
            //如果有缓存，就直接取
            $allProduct = Cache::get('allProductByCode' . $code);
        } else {
            $language_id = LanguageModel::getLanguageCodeOrID($code);
            $product = $this::order("category_id desc")->all([
                'language_id' => $language_id,
                'status' => 1
            ]);
            $productArr = collection($product)->toArray();
            //对所有的产品数组进行排序
            $sortProduct = AlgorithmHelp::array_sort($productArr, 'url_title');
            //设置缓存
            Cache::set('allProductByCode' . $code, $sortProduct, 7200);
            //获取缓存的值
            $allProduct = Cache::get('allProductByCode' . $code);
        }
        //todo::还需优化的地方，把传入的$url_title换成id
        $mid = AlgorithmHelp::binarySearch($allProduct, $url_title, 'url_title');
        if ($mid === false) {
            return false;
        } else {
            return $allProduct[$mid];
        }
    }
}