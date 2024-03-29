<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/1/2
 * Time: 14:56
 */

namespace app\common\model\Service;

use app\common\model\BaseModel;
use app\common\model\Language as LanguageModel;
use think\Collection;

class GuestBook extends BaseModel
{
//    protected $autoWriteTimestamp = 'datetime';
    protected $table = "tb_guest_book";

    public function getDataById($id)
    {
        try {
            return $this->find($id);
        } catch (\Exception $e) {
            //手工抛出异常
            return throwException($e->getMessage());
        }
    }

    /**
     * @param $user_id
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getDataByUserId($user_id)
    {
        try {
            $result = $this->where('user_id', $user_id)->select();
        } catch (\Exception $e) {
            return throwException($e->getMessage());
        }
        return $result;
    }

    //前端点击固件申请添加后台数据
    public function addTicket($data, $code)
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $map = ['status' => -1, 'language_id' => $language_id, 'email' => $data['email']];
        $counts = $this->where($map)->count();
        if ($counts >= 3) {
            return false;
        } else {
            $data['status'] = -1;
            $data['language_id'] = $language_id;
            $this->save($data);
            //同时更新listorder字段，把id的值赋给它
            return $this->allowField('listorder')
                ->isUpdate(true)
                ->save(['listorder' => $this->id + 50], ['id' => $this->id]);
        }
    }

    /**
     * @param $status
     * @param $language_id
     * @param string $field
     * @return \PHPUnit_Framework_MockObject_Stub_Exception
     *
     */
    public function getDataByLanguage($status,$language_id,$field=''){
        $map=['status'=>$status,'language_id'=>$language_id];
        try{
            if(empty($field) or !isset($field)){
                $data=$this->where($map)->select();
            }else{
                $data=$this->where($map)->field($field)->select();
            }
            return Collection::make($data)->toArray();
        } catch (\Exception $e){
            return throwException($e->getMessage());
        }
    }
}