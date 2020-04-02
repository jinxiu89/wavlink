<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/4/2 14:41
 * @User: admin
 * @Current File : product.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\customer\controller;


use app\customer\validate\warranty as WarrantyValidate;

/**
 * Class product
 * @package app\customer\controller
 */
class product extends Base
{
    public function register()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            if (strtotime($data['prd_time']) - strtotime($data['create_time']) < 0) {
                //执行保存操作
                $data['user_id'] = $this->getLoginCustomer();
                $data['status'] = 2;
                $data['create_time'] = date('Y-m-d', time());
                $data['expiry_time'] = date("Y-m-d", strtotime("+1 year", strtotime($data['create_time'])));
                $validate = new WarrantyValidate();
                if (!$validate->check($data)) {
                    return show(0, '', '', '', '', $validate->getError());
                } else {
                    if ((new WarrantyModel())->allowField(true)->save($data)) {
                        return show(1, '', '', '', '', lang('Success'));
                    } else {
                        return show(0, '', '', '', '', lang('Unknown Error'));
                    }
                }

            } else {
                return show(0, '', '', '', '', lang('The “Purchase Time” has to be later than the Date of Manufacture in your registration.'));
            }
        }
        return $this->fetch();
    }
}