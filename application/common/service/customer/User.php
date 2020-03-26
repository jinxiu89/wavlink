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

use app\common\model\Customer;
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
        $this->model = new Customer();
    }

    /**
     * @param $data
     * @return array
     */
    public function login($data)
    {
        try {
            $user = $this->model->get(['email' => $data['email']]);
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
            return ['status' => 0, 'message' => lang('Server Error'), 'url' => url('customer_login')];
        }
    }
}