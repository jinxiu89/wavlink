<?php
/**
 * Created by PhpStorm.
 * User: web JinXiu89@163.com
 * Date: 2018/8/21
 * Time: 17:31
 * 命名规范：文件名首字母大写
 * 类 首写字母大写
 * 函数 驼峰命名  如getInfo
 */

namespace app\customer\controller;

use app\common\model\Customer\Warranty as WarrantyModel;
use app\customer\validate\warranty as WarrantyValidate;

class Warranty extends Base
{
    public function index()
    {
        $user_id = $this->getLoginCustomer();
        $result = (new WarrantyModel())->where('user_id', $user_id)->select();
        return $this->fetch(
            '',
            [
                'result' => $result
            ]
        );
    }

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

    public function extend($id)
    {
        ////状态 1，已审核 2，审核中 3，正在处理保修 4,已完成, 5,已延保
        $model = new WarrantyModel();
        $data = $model->getDataById($id);
        $data['expiry_time'] = date("Y-m-d", strtotime("+3 month", strtotime($data['expiry_time'])));
        if ($data['status'] == 1) {
            $data['status'] = 5;
            if ($data->allowField('expiry_time,status')->save($data)) {
                return show(1, '', '', '', '', lang('Success'));
            } else {
                return show(0, '', '', '', '', lang('The extended warranty operation can only be performed once. It is not allowed to prolong the extended warranty by repeating the operation.'));
            }
        } elseif ($data['status'] == 2) {
            return show(0, '', '', '', '', lang('Products under review cannot perform extended warranty operation.'));
        } elseif ($data['status'] == 3) {
            return show(0, '', '', '', '', lang('If you are in the process of enjoying the warranty service, you can’t perform the extended warranty operation.'));
        } elseif ($data['status'] == 5) {
            return show(0, '', '', '', '', lang('The extended warranty operation can only be performed once. It is not allowed to prolong the extended warranty by repeating the operation.'));
        }
    }

    public function apply($id)
    {
        //todo::申请操作
    }

    public function details(){
        //todo::查看自己的该产品的保修状态
    }
}