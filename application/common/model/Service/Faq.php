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
use app\common\model\ServiceCategory as ServiceCategoryModel;

/**
 * Class Faq
 * @package app\common\model
 */
Class Faq extends BaseModel
{
    protected $table = 'faq';

    public function getFaqByCategoryID($id = '', $code, $order = 'desc')
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        if (empty($id)) {
            $data = [
                'status' => 1,
                'language_id' => $language_id
            ];
        } else {
            $data = [
                'status' => 1,
                'language_id' => $language_id,
                'category_id' => $id
            ];
        }
        $field = 'id,name,url_title,update_time';
        $order = [
            'update_time' => $order,
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        $count=$this->where($data)->count();
        $result=$this->where($data)->order($order)->field($field)->paginate('',true);
        return ['count'=>$count,'data'=>$result];
    }

    public function getSelectFaq($code, $key)
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $model = 'Faq';
        $map['status'] = 1;
        $map['name|url_title|problem|answer|relevantproblem'] = array('like', '%' . $key . '%');
        $map['language_id'] = $language_id;
        $order = [
            'id' => 'desc',
        ];
        return Search($model, $map, $order);
    }
}