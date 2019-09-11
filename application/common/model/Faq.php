<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */
namespace app\common\model;
use app\common\model\Language as LanguageModel;
use app\common\model\ServiceCategory as ServiceCategoryModel;
Class Faq extends BaseModel
{
    protected $table = 'faq';
    public function getFaqByCategoryID($id='',$code){
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        if (empty($id)){
            $data = [
                'status'=>1,
                'language_id'=>$language_id
            ];
        }else{
            $data = [
                'status' => 1,
                'language_id' => $language_id,
                'category_id' => $id
            ];
        }

        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        return Search('faq',$data,$order);
    }
    public function getSelectFaq($code,$key){
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