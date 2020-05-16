<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/9/1
 * Time: 10:36
 */

namespace app\common\model\Service;



use app\common\model\BaseModel;

class OldSn extends BaseModel
{
    protected $autoWriteTimestamp = 'datetime';
    protected $table = 'tb_sn_old';

    public function getDateByStatus()
    {
        $model = 'OldSn';
        $map = [];
        $order = [
            'id' => 'asc',
        ];
        return Search($model, $map, $order);
    }

    /***
     * @param $sn
     * @return mixed
     */
    public function getDataBySn($sn)
    {
        if (!isset($sn) and strlen($sn) <= 15) {
            return show(0, '', '', '', '', '序列号长度错误');
        }
        $prefix = substr($sn, 0, 12);
        $count = intval(substr($sn, 12,4));
        if (!empty($this)) {
            $data = $this->getSnDataByPrefix($prefix, $count);
            if(empty($data) || !isset($data) | !is_array($data)){
                return toJson(array(
                    'status'=>0,
                    "data"=>"没有查询到结果"
                ));
            }
            if($count > 0 and $count <= $data['count']){
                //还需要给型号,分类
                $model=(new Model())->getDataByModel_id($data['model_id']);
                $cate=(new Cate())->getDescriptionByCode($model['cate']);
                $data['models']=$model;
                $data['cate']=$cate;
                $data['erp_no']=$data['prefix'];
                return toJson($data);
            }else{
                return toJson(array(
                    'status'=>0,
                    "data"=>"没有查询到结果"
                ));
            }
        }
    }

    public function getSnDataByPrefix($prefix,$count)
    {
        try {
            $data = $this->where('prefix', $prefix)->find();
            return !empty($data) ? $result = $data->toArray() : [];
        } catch (\Exception $e) {
            return false;
        }
    }
}