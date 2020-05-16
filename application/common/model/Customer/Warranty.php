<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/9/27
 * Time: 16:40
 */

namespace app\common\model\Customer;
use app\common\model\BaseModel;



class Warranty extends BaseModel
{
    protected $autoWriteTimestamp = 'datetime';
    protected $table = 'tb_warranty';
    public function getDataById($id){
        try{
            return $this->find($id);
        }catch (\Exception $e){
            //手工抛出异常
            return throwException($e->getMessage());
        }
    }
}