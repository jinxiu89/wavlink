<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */
namespace app\common\model;

Class AuthGroup extends BaseModel
{
    protected $table = "auth_group";
//    //用户和组 中间关联，第一个 关联模型，第二个参数 中间关联模型 ，第三个 关联表对应的外键，第四个 当前表对应的外键
    public function manger(){
        return $this->belongsToMany('Manger','\app\common\model\AuthGroupAccess','uid','group_id');
    }
    public function getAuthGroup() {
        return $this->getDataByOrder('');
    }
    public static function getAuthById($id){
        $auth =  self::relation('AuthRule')->find($id);
        return $auth;
    }
    public function getAllAuthGroup(){
        $data = [
            'status' => 1
        ];
        return $this->where($data)->select();
    }
    public function getGroupTitle($arr){
        return $this->where('id','in',$arr)->where('status',1)->field('title')->select();
    }
}