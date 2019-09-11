<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */
namespace app\common\model;

use think\Model;

Class Vistor extends BaseModel
{
    protected $table = 'tb_vistor';
   public function getUser($map){
     return $this->where($map)->find();
   }
}