<?php
/**
 * Created by PhpStorm.
 * User: web jinxiu89@163.com
 * Date: 2018/8/18
 * Time: 11:35
 * 命名规范：文件名首字母大写
 * 类 首写字母大写
 * 函数 驼峰命名  如getInfo
 */

namespace app\customer\controller;

use app\common\model\Country;
use app\common\model\Customer;

class Info extends Base
{
    public function index()
    {
        //todo:
        return "hello info index";
    }

    public function info()
    {
        $id = session('CustomerInfo', '', 'Customer');
        $user = new Customer();
        if (request()->isAjax()) {
            $data = input('post.',[],'htmlspecialchars');
            $result = $user->upDateById($data, $id);
            if ($result == true) {
                return show(1, lang('Success'), '', '', '/customer/info');
            } else {
                return show(0, lang('Success'), '', '', '/customer/info');
            }
        }
        $customer = $user->get($id);
        $country = (new Country())->field('country_id,name')->select();

        if (isMobile()) {
            return "hello world";
        } else {
            return $this->fetch('info/index', [
                'country' => $country,
                'result' => $customer
            ]);
        }
    }

    public function editPassword()
    {
        $id = session('CustomerInfo', '', 'Customer');
        if (request()->isAjax()) {
            $data = input('post.');
            //核对老密码
            $user = new Customer();
            $oldPassword = $data['oldPassword'];
            $result = $user->CheckPassword($oldPassword, $id);
            if ($result == true) {
                $result = $user->upDateById($data, $id);
            } else {
                return show(0, lang('Old Password input Error'), '', '', '', '');
            }
            if ($result == true) {
                return show(1, lang('Success'), '', '', '/customer/info');
            } else {
                return show(0, lang('Success'), '', '', '/customer/info');
            }
        }
    }

}