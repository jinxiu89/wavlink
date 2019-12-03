<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */

namespace app\common\model;

use app\common\model\Language as LanguageModel;
use think\Collection;

/**
 * Class Drivers
 * @package app\common\model
 */
Class Drivers extends BaseModel
{
    protected $table = "drivers";

    // 前台 获取当前选择的子分类下的驱动列表，models产品型号字段处理

    /**
     * @param $code
     * @param $categoryId
     * @param string $order
     * @return mixed|\think\Paginator
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getDriversByCategoryId($code, $categoryId, $order = "desc")
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $data = [
            'status' => 1,
            'language_id' => $language_id,
        ];
        $order = [
            'update_time' => $order,
            'listorder' => 'desc',
            'id' => 'desc',
        ];
        if (empty($categoryId)) {
            $count = $this->where($data)->count();
            $data = $this->where($data)->order($order)->paginate(6, true);
        } else {
            $count = $this->where($data)->where('category_id', '=', $categoryId)->count();
            $data = $this->where($data)->where('category_id', '=', $categoryId)->order($order)->paginate(6, true);
        }
        $result = ModelsArr($data, 'models', 'modelsGroup');
        return ['count' => $count, 'result' => $result];
    }

    /**
     * @param $name
     * @param $code
     * @return array|string
     * 驱动搜索
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getSelectDrivers($name, $code)
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $model = 'Drivers';
        $map['status'] = '1';
        $map['name|url_title|keywords|description|models'] = array('like', '%' . $name . '%');
        $map['language_id'] = $language_id;
        $order = [
            'id' => 'desc',
        ];
        return Search($model, $map, $order);
    }

    /**
     * @param $language_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 创建索引
     */
    public static function allData($language_id){
        $data = self::where(['language_id' => $language_id, 'status' => 1])->field('id,name,url_title,models,seo_title,keywords,descrip,status,listorder')->select();
        return Collection::make($data)->toArray();
    }
}