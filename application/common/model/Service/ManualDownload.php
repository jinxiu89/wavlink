<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/12/18
 * Time: 17:07
 */

namespace app\common\model\Service;


use app\common\model\BaseModel;

/**
 * Class Manual
 * @package app\common\model
 */
class ManualDownload extends BaseModel
{
    protected $table = "tb_manual_download";
    protected $autoWriteTimestamp = 'date';

    public function addManual($data)
    {
        $res = $this->where(['manual_id' => $data['manual_id'], 'language' => trim($data['language'])])->select();
//        print_r($res);exit;
        if($res->isEmpty()){
            return $this->add($data);
        }else{
            exception("已存在该语种，不能重复添加！");
        }
    }

    public function manual(){
        return $this->belongsTo('Manual');
    }
    public function getDataByLanguage($language_id)
    {
        $result = $this->where(['status' => 1, 'language_id' => $language_id])->paginate(6);
        return ModelsArr($result, 'model', 'modelGroup');
    }
}