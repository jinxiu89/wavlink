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

namespace app\common\model;


class Cate extends BaseModel
{
    protected $autoWriteTimestamp = 'datetime';
    protected $table = 'tb_cate';

    public function getCateByStatus(){
        $model='Cate';
        $map=[];
        $order=['id'=>'asc',];
        return Search($model,$map,$order);
    }

    /**
     * @param $cate
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getDescriptionByCode($cate){
        return $this->where('code','like',$cate)->find()['description'];
    }
}