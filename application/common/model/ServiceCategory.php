<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */

namespace app\common\model;

use app\common\model\Language as LanguageModel;
use app\common\helper\Category as Helper;
use think\Collection;

/***
 * Class ServiceCategory
 * @package app\common\model
 */
Class ServiceCategory extends BaseModel
{
    protected $table = 'service_category';

    /**
     * 获取状态status=1的数据,并按照id倒序的方式排序出来在index下
     * 根据parent_id得到
     * 获取一级栏目
     * @param int $parentId
     * @param $language_id
     * @return \think\Paginator
     */
    public function getServiceCategory($parentId = 0, $language_id = '')
    {
        $model = 'ServiceCategory';
        $data = [
            'parent_id' => $parentId,
            'language_id' => $language_id
        ];
        $order = [
            'status' => 'desc',
            'listorder' => 'desc',
            'id' => 'desc',
        ];
        return Search($model, $data, $order);
    }

    /**
     * @param $value
     * @param $url_title
     * @return array|false|\PDOStatement|string|\think\Model\
     * 根据分类的url_title,语言来获取分类
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getCategoryIdByName($value, $url_title)
    {
        $language_id = LanguageModel::getLanguageCodeOrID($value);
        $data = ['status' => 1, 'url_title' => $url_title, 'language_id' => $language_id,];
        $result = self::where($data)->find();
        return $result;
    }

    public static function getNavByCategoryId($code, $category)
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $result = self::where(['status' => 1, 'url_title' => $category, 'language_id' => $language_id])->find();
        return TurnArray(getParents(self::all(), $result['id']));
    }

    /***
     * 返回指定的parent_id 的所有子分类
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getChild($id)
    {
        $child = self::where(array('parent_id' => $id))->select();
        return TurnArray($child);
    }

    /***
     * 如果它没有子类，返回他所有的同级分类
     * @param $parent_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getSameChild($parent_id)
    {
        $child = self::where(['parent_id' => $parent_id,])->select();
        $data = TurnArray($child);
        return $data;
    }

    /**
     * @param $value
     * @param $name
     * @return array|false|\PDOStatement|string|\think\Model \获取服务管理 服务分类
     * \获取服务管理 服务分类 一级分类
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @internal param int $parentId
     * @internal param $code
     * 传过来的$value 可以是语言code 也可以是语言id，最终都会以语言id去查询
     *  $name 是一级分类的 url_title
     */
    public static function getTopCategory($value, $name)
    {
        $language_id = LanguageModel::getLanguageCodeOrID($value);
        $map = [
            'status' => 1,
            'language_id' => $language_id,
            'url_title' => $name,
            'parent_id' => 0
        ];
        return self::where($map)->field('id,name,url_title,description,keywords,seo_title,path,parent_id')->find();
    }

    //获取对应栏目的二级分类

    /***
     * 如果$name 一级分类变量为空，则调用这个方法的控制器名
     * if (empty($name)){
     * $cate = request()->controller();
     * value 为当前的语言code，如en_us 是当前语言code ,
     * 而我们的模块名是按照code来起名的，所以在初始化_initialize时获取当前模块名
     * @param $value
     * 一级分类名，单指 url_title 字段的值
     * @param string $name
     * @return false|\PDOStatement|string|\think\Collection
     * 获取到某个分类下面的二级分类
     *
     * self:: 指类本身
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getSecondCategory($value, $name = '')
    {
        $cate = empty($name) ? request()->controller() : $name;
        $parent = self::getTopCategory($value, $cate);
        $data = ['status' => 1, 'parent_id' => $parent['id']];
        $order = ['listorder' => 'desc', 'id' => 'desc'];
        return (new ServiceCategory)->where($data)->order($order)
            ->field('id,name,image,description,keywords,language_id,url_title,seo_title')->select();
    }

    /**
     * @param $language
     * @param string $name
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getTree($language, $name = '')
    {
        $cate = empty($name) ? request()->controller() : $name;
        $parent = self::getTopCategory($language, $cate)->toArray();
        $data = self::where('path', 'like', $parent['path'] . $parent['id'] . '-%')
            ->field('id,name,url_title,parent_id,path,level,listorder')
            ->order(['listorder' => 'desc'])->select()->toArray();
        $data[] = $parent;
        return Helper::toLevel($data, '&nbsp;', $parent = 0);
    }

    /**
     * @param $language
     * @param $path
     * @param $parent_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getCategoryByPath($path, $parent_id)
    {
        $categoryId=[];
        $category=self::where('path','like',$path.$parent_id.'-%')->field('id')->select()->toArray();
        foreach ($category as $item){
            $categoryId[]=$item['id'];
        }
        return $categoryId;
    }

    /***
     * 获取3级分类
     */
    public static function getCategory($code, $category)
    {
        $parent = self::where(['name' => $category, 'language_id' => $code])->find();
        $childs = self::where(['language_id' => $code,])->select();
        $data = TurnArray($childs);
        $res = getChilds($data, $parent['id']);
        return $res;
    }
}