<?php
/**
 * Created by PhpStorm.
 * User: web jinxiu89@163.com
 * Date: 2018/8/29
 * Time: 15:18
 * 命名规范：文件名首字母大写
 * 类 首写字母大写
 * 函数 驼峰命名  如getInfo
 */

namespace app\common\model\Service;


use app\common\model\BaseModel;

class Model extends BaseModel
{
    protected $autoWriteTimestamp = 'datetime';
    protected $table = 'tb_model';


    public function getDateByStatus($map=[])
    {
        $model = 'Model';
        $order = [
            'id' => 'asc',
        ];
        return Search($model, $map, $order);
    }

    public function getDataByModel_id($model_id)
    {
        return $this->find($model_id);
    }

    public function soft()
    {
        return $this->belongsToMany('Soft', 'model_soft', 'soft_id', 'model_id');
    }
    public function Sn(){
        return $this->hasMany('Sn','model_id','id');
    }
    public function getDataByCode($code){
        return $this->where('code','like',$code)->find();
    }

}