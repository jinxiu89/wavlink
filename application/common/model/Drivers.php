<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */
namespace app\common\model;

use app\common\model\Language as LanguageModel;

Class Drivers extends BaseModel
{
    protected $table = "drivers";


    // 前台 获取当前选择的子分类下的驱动列表，models产品型号字段处理
    public function getDriversByCategoryId($code, $categoryId) {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $data = [
            'status' => 1,
            'language_id' => $language_id,
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc',
        ];
        if (empty($categoryId)) {
            $result = $this->where($data)->order($order)->paginate(6);
        } else {
            $result = $this->where($data)->where('category_id', '=', $categoryId)->order($order)->paginate(6);
        }
        $result = ModelsArr($result, 'models', 'modelsGroup');
        return $result;
    }

    /**
     * @param $name
     * @param $code
     * @return array|string
     * 驱动搜索
     */
    public static function getSelectDrivers($name, $code) {
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
}