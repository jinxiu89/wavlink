<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */
namespace app\common\model;

use think\Model;

Class UserGroupAccess extends Model
{
    protected $autoWriteTimestamp = false;
    protected $table = 'auth_group_access';
    public function getGroup($id){
        return $this->where(array('uid'=>$id))->find();
    }
}