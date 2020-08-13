<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/5/20 14:51
 * @User: admin
 * @Current File : DriversCategory.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\common\model\Service;


use app\common\model\BaseModel;
use Exception;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\model\relation\HasMany;
use think\Paginator;
//use app\common\model\Service\Drivers;

/**
 * Class DriversCategory
 * @package app\common\model\Service
 */
class DriversCategory extends BaseModel
{
    protected $table = 'tb_drivers_category';

    /**
     * @return HasMany
     */
    public function drivers(){
        return $this->hasMany(Drivers::class,'category_id')
            ->field('id,name,category_id');
    }
    /**
     * @param string $status
     * @param string|null $language_id
     * @return mixed|Paginator
     */
    public function getDataByLanguageId($status, $language_id)
    {
        if (empty($status)) return self::where(['language_id' => $language_id])->order(['level', 'id']);
        return self::where(['status' => 1, 'language_id' => $language_id])->order(['level', 'id']);
    }

    /**
     * @param $language_id
     */
    public function getDataByCategory($language_id){
        return self::with('drivers')->where(['status'=>1,'language_id'=>$language_id])
            ->field('id,parent_id,is_parent,level,path,name,title,url_title,listorder')
            ->order(['level'=>'desc','id'=>'asc','listorder'=>'desc'])->select();
    }


    /**
     * @param $data
     * @return bool
     */
    public function saveData($data)
    {
        try {
            return self::allowField(true)->save($data, ['id' => $data['id']]);
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param $category
     * @param $language_id
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     * 根据分类title 找到他自己和下一级分类ID的集合
     *
     */
    public function getCategoryID($category, $language_id)
    {
        $category = self::where(['title' => $category, 'language_id' => $language_id])->find();
        $categoryID[] = $category->id;
        if ($category->is_parent == 0) { // 0 代表目录 1 代表子分类
            $path = $category->path;
            $categorys = self::where('path', 'like', $path . $category->id . '%')->select();
            $categoryID = array_merge($categoryID, array_column($categorys->toArray(), 'id'));
        }
        return ['category'=>$category,'categoryID'=>$categoryID];
    }
}