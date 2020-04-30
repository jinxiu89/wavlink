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
use think\db\Query;
use think\Exception;
use think\facade\Session;

/**
 * Class User
 * @package app\common\service\customer
 */
class User extends BaseService
{
    public function __construct()
    {
        $this->model = new UserModel();
    }

    /**
     * @param $email
     * @return bool
     */
    public function CheckEmail($email)
    {
        try {
            return $this->model->CheckEmail($email);
        } catch (\Exception $exception) {//todo:日志再完善哈
            return $exception->getMessage();
        }
    }

    /**
     * @param $phone
     * @return array|bool
     */
    public function CheckPhone($phone)
    {
        try {
            return $this->model->CheckPhone($phone);
        } catch (\Exception $exception) {//todo::日志再完善
            return $exception->getMessage();
        }
    }

    /**
     * @param $id
     * @return Query|null
     */
    public function getUserWithInfoByID($id)
    {
        try {
            return $this->model->with('info')->get($id);
        } catch (\Exception $exception) {
            //todo:hello 等一下搞
        }
    }

    /**+
     * @param $data
     * @return bool|string
     *
     */
    public function changeInfo($data)
    {
        try {
            $user = $this->model->get($data['id']);
            $info = $user->info;
            unset($data['id']);
            if (!empty($info)) {
                return $user->info->save($data) ? true : false;
            }
            return $user->info()->save($data) ? true : false;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @param $data
     * @return bool|string
     * 小方：桑葚10g，槐米6g，决明子6g。久坐便秘者。
     * 加班严重，喝可乐是没用的，需要调理一下身体，别到30岁就干不动了。
     * 熬夜加班者可以使用这个方子泡茶喝，上述一天的分量，不分时候喝
     */
    public function updateInfo($data)
    {
        try {
            $user = $this->model->get($data['id']);
            return $user->save($data) ? true : false;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /***
     * @param $id
     * @return mixed
     */
    public function getInfo($id)
    {
        try {
            return $this->model->get($id);
        } catch (\Exception $exception) {
            //TODO:
        }
    }

    /**
     * @param string $email
     * @return array
     *
     */
    public function getUserByEmail($email = '')
    {
        try {
            $result = $this->model->getUserByEmail($email);
            return $result->toArray();
        } catch (\Exception $exception) {
            return [];
        }
    }

    /**
     * @param string $phone
     * @return array
     */
    public function getUserByPhone($phone = '')
    {
        try {
            $result = $this->model->getUserByPhone($phone);
            return $result->toArray();
        } catch (\Exception $exception) {
            return [];
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getDataByIdWithInfo($id)
    {
        try {
            return $this->model->withJoin('info', 'left')->get($id);
        } catch (\Exception $exception) {
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
        $user = [];
        try {
            if (isset($data['email']) || !empty($data['email'])) {
                $user = $this->model->get(['email' => $data['email']]);
            }
            if (isset($data['phone']) || !empty($data['phone'])) {
                $user = $this->model->get(['phone' => $data['phone']]);
            }
            if (!$user) {
                return ['status' => 0, 'message' => lang('User does not exist'), 'url' => url('customer_login')];
            }
            if ($user->password !== GetPassword($data['password'])) {
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
                $session['id']=$user['id'];
                $session['username']=$user['username'];
                Session::set('CustomerInfo', $session, 'Customer');
                return ['status' => 1, 'message' => lang('Success'), 'url' => url('customer_info')];
            }
            return ['status' => 0, 'message' => lang('未知错误'), 'url' => url('customer_login')];
        } catch (\Exception $exception) {
            return ['status' => 0, 'message' => $exception->getMessage(), 'url' => url('customer_login')];
        }
    }

    /**
     * @param $data
     * @return UserModel|string
     * 关联保存
     */
    public function reg($data)
    {
        //todo::注册操作
        try {
            $user= $this->model->create($data);  //返回的是一个当前模型的实例
            $info=$this->model->get($user->id);
            if($info->info()->save(['user_id'=>$user->id,'gender'=>3])) return $user;
        } catch (\Exception $exception) {
            return $exception->getMessage();//todo:: 异常
        }

    }
}