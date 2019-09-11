<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/1/2
 * Time: 14:56
 */

namespace app\common\model;

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
        $map = [
            'status' => -1,
            'language_id' => $language_id,
            'email' => $data['email']
        ];
        $counts = $this->where($map)->count();
        if ($counts >= 3) {
            return false;
        } else {
            $data['status'] = -1;
            $data['language_id'] = $language_id;
            $this->save($data);
            $id = $this->id;
            //同时更新listorder字段，把id的值赋给它
            return $this->allowField('listorder')
                ->isUpdate(true)
                ->save(
                    ['listorder' => $id + 50],
                    ['id' => $id]
                );
        }
    }
    public function getDataByLanguage($status,$language_id){
        $map=['status'=>$status,'language_id'=>$language_id];
        try{
            return \collection($this->where($map)->select())->toArray();
        } catch (\Exception $e){
            return throwException($e->getMessage());
        }
    }
}