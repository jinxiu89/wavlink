<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */
namespace app\common\model;
use app\common\model\Language as LanguageModel;

/***
 * Class Setting
 * @package app\common\model
 * 系统设置
 */
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

    /**
     * @param string $code
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getSeo($code='en_us'){
        $language_id =LanguageModel::getLanguageCodeOrID($code);
        $map=[
            'status'=>1,
            'language_id'=>$language_id
        ];
        return $this->where($map)->find();
    }
}