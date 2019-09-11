<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/9/11
 * Time: 9:52
 */

namespace app\common\model;


class Soft extends BaseModel
{
    protected $autoWriteTimestamp='date';
    protected $table = 'tb_soft';

     function  getByVer($ver){
        return $this->where('ver',$ver)->find();
    }

    function models()
    {
        return $this->belongsToMany('Model', 'model_soft','model_id', 'soft_id');
    }
    public function getDateByStatus($map=[]){
        $model='Soft';
        $order=[];
        return Search($model,$map,$order);
    }
}