<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */

namespace app\common\model\Service;

use app\common\model\BaseModel;
use app\common\model\Language as LanguageModel;
use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\Paginator;

/**
 * Class Drivers
 * @package app\common\model
 */
class Drivers extends BaseModel
{
    protected $table = "tb_drivers";

    // 前台 获取当前选择的子分类下的驱动列表，models产品型号字段处理

    /**
     * @param $language
     * @param string $order
     * @return mixed|Paginator
     * @throws DbException
     */
    public function getDriversByLanguage($language, $order = "desc")
    {
        $data = ['status' => 1, 'language_id' => $language,];
        $order = ['update_time' => $order, 'listorder' => 'desc', 'id' => 'desc',];
        $response=self::where($data);
        $count = $response->count();
        $data = $response->order($order)->paginate(6, true);
        $result = ModelsArr($data, 'models', 'modelGroup');
        return ['count' => $count, 'data' => $result];
    }

    /**
     * @param $language
     * @param $category
     * @param string $order
     * @return array
     * @throws DbException
     */
    public function getDriversByCategoryIds($language, $category, $order = 'desc')
    {
        $order = ['update_time' => $order, 'listorder' => 'desc', 'id' => 'desc'];
        $response = self::where('category_id', 'in', $category)->where(['language_id' => $language, 'status' => 1]);
        $count = $response->count();
        $data = $response->order($order)->paginate(6, true);
        $result = ModelsArr($data, 'models', 'modelGroup');
        return ['count' => $count, 'data' => $result];
    }

    /**
     * @param $name
     * @param $code
     * @return array|string
     * 驱动搜索
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function getSelectDrivers($name, $code)
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $model = 'tb_drivers';
        $map['status'] = '1';
        $map['name|url_title|keywords|description|models'] = ['like', '%' . $name . '%'];
        $map['language_id'] = $language_id;
        $order = ['id' => 'desc'];
//        return self::where($map)->order($order)->paginate('',true);
        //todo:: 搜索这个地方官改下一版优化后台搜索功能更时优化功能
        //notice:这个地方影响到产品驱动搜索，后期要优化时要注意切分开
        return Search($model, $map, $order);
    }

    /**
     * @param $language_id
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     * 创建索引
     */
    public static function allData($language_id)
    {
        $data = self::where(['language_id' => $language_id, 'status' => 1])->field('id,name,url_title,models,seo_title,keywords,descrip,status,listorder')->select();
        return Collection::make($data)->toArray();
    }
}