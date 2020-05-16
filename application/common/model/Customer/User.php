<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */

namespace app\common\model\Customer;

use think\Exception;
use think\facade\Session;
use app\common\model\BaseModel;
use think\model\relation\HasOne;

/**
 * Class Customer
 * @package app\common\model\customer
 */
Class User extends BaseModel
{
    protected $autoWriteTimestamp = 'datetime';
    protected $table = 'tb_user';

    /**
     * @return HasOne
     * 用户信息表关联模型
     */
    public function info(){
        return $this->hasOne(UserInfo::class)->bind('first_name,last_name,title,level');
    }

    public function searchPhoneAttr($query,$value,$data){
        $query->where('name',$value.'%');
    }

    /**
     * @param $id
     */
/*    public function getDataByIdWithInfo($id){

    }*/

    /**
     * 这个是登录用的
     * @param $data
     * @return int
     */
    public function CheckCustomer($data)
    {
        $email = $this::get(['email' => $data['email']]);
        //验证邮箱
        if (!$email) {
            return -1;//用户不存在
        }
        //验证密码
        if ($email['password'] !== GetPassword($data['password'])) {
            return -2;//密码不正确
        }
        $customerUpdateData = [
            'last_login_time' => date("Y-m-d H:i:s", time()),
            'last_login_ip' => request()->ip(),
        ];
        $this->upDateById($customerUpdateData, $email['id']);
        Session::set('CustomerInfo', $email['id'], 'Customer');
        return 1;
    }

    /**
     * @param $email
     * @return bool
     */
    public function CheckEmail($email)
    {
        $result = $this::get(['email' => $email]);
        if (!$result) {
            //不存在或者被禁用了 注册即可以操作
            return false;
        } else {
            //有 就表示可以执行下一步操作 登录即可以操作
            return true;
        }
    }

    /**
     * @param $phone
     * @return bool
     */
    public function CheckPhone($phone){
        $result=$this::get(['phone'=>$phone]);
        if(!$result){
            return false;
        }else{
            return true;
        }
    }

    /**
     * @param $email
     * @return mixed
     */
    public function getUserByEmail($email){
        return $this::get(['email'=>$email]);

    }

    /**
     * @param $phone
     * @return mixed
     */
    public function getUserByPhone($phone){
        return $this::get(['phone'=>$phone]);
    }

    public function CheckPassword($oldPassword,$id){
        $result = $this::get($id);
        if($result['password'] == GetPassword($oldPassword)){
            return true;
        }else{
            return false;
        }
    }

    public function upDateById($data, $id)
    {

        $result = self::array_empty($data);
        if (array_key_exists("password", $result)) {
            $result['password'] = GetPassword($result['password']);
        }
        //allowField 过滤data数组中非数据表中的数据
        return $this->allowField(true)->save($result, ['id' => $id]);
    }

    /**
     * @param $data
     * @return bool
     */
    public function reg($data)
    {
        $user = $this::get(['email' => $data['email']]);
        if ($user || !$data['email']) {
            //这里不能注册 就是已存在
            return false;
        } else {
            try {
                $data['password'] = GetPassword($data['password']);
                return $this->allowField(true)->save($data);
            } catch (Exception $e) {
                throwException($e->getMessage());
            }
        }
    }

    public function getNameById($id)
    {
        return $this::get($id);
    }

    public function getNameByEmail($email)
    {
        $result = $this::get(['email' => $email]);
        if (!$result){
            return false;
        }elseif ($result){
            return $result['id'];
        }else{
            return false;
        }
    }
}