<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */
namespace app\common\model;
use app\common\model\Language as LanguageModel;
class Setting extends BaseModel
{
    protected $table ="setting";
    public function getSetting(){
        $order=[
            'status'=>'desc',
            'id'=>'desc',
        ];
        return $this->order($order)->paginate();
    }
    public function getSeo($code='en_us'){
        $language_id =LanguageModel::getLanguageCodeOrID($code);
        $map=[
            'status'=>1,
            'language_id'=>$language_id
        ];
        return $this->where($map)->find();
    }
}