<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/3/26 15:36
 * @User: admin
 * @Current File : User.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\common\service\customer;

use app\common\model\customer\User as UserModel;
use think\facade\Session;

/**
 * Class User
 * @package app\common\service\customer
 */
class User extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new UserModel();
    }

    /**
     * @param $email
     * @return bool
     */
    public function CheckEmail($email){
        try{
            return $this->model->CheckEmail($email);
        }catch (\Exception $exception){//todo:日志再完善哈
            return $exception->getMessage();
        }
    }

    /**
     * @param $phone
     * @return array|bool
     */
    public function CheckPhone($phone){
        try{
            return $this->model->CheckPhone($phone);
        }catch (\Exception $exception){//todo::日志再完善
            return $exception->getMessage();
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getDataByIdWithInfo($id){
        try{
            return $this->model->withJoin('info','left')->get($id);
        }catch (\Exception $exception){
            //todo 异常处理
        }
    }

    /**
     * @param $data
     * 当用户的基本信息被确认存在且一切正常之后，执行登录操作
     * @return array
     */
    public function login($data)
    {
        try {
            $user = $this->model->CheckEmail(['email' => $data['email']]);
            if (!$user) {
                return ['status' => 0, 'message' => lang('User does not exist'), 'url' => url('customer_login')];
            }
            if ($user['password'] !== GetPassword($data['password'])) {
                return ['status' => 0, 'message' => lang('Password Error'), 'url' => url('customer_login')];
            }
        } catch (\Exception $exception) {
            return ['status' => 0, 'message' => lang('Server Error'), 'url' => url('customer_login')];
        }
        try {
            $customerUpdateData = [
                'last_login_time' => date("Y-m-d H:i:s", time()),
                'last_login_ip' => request()->ip(),
            ];
            if ($this->model->upDateById($customerUpdateData, $user['id'])) {
                Session::set('CustomerInfo', $user['id'], 'Customer');
                return ['status' => 1, 'message' => lang('Success'), 'url' => url('customer_info')];
            }
            return ['status' => 0, 'message' => lang('未知错误'), 'url' => url('customer_login')];
        } catch (\Exception $exception) {
            return ['status' => 0, 'message' => $exception->getMessage(), 'url' => url('customer_login')];
        }
    }
    public function reg($data){
        //todo::注册操作
    }
}