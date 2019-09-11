<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/9/26
 * Time: 14:23
 */

namespace app\customer\controller;


use app\common\model\OldSn;
use app\common\model\Sn as SnModel;

/**
 * Class Sn
 * @package app\en_us\controller
 */
class Sn extends Base_reg
{
    public function index()
    {
        return $this->fetch();
    }

    public function Verification()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            if (!empty($data)) {
                $sn = $data['sn'];
                if (is_numeric(substr($sn, 0, 4))) {
                    return (new OldSn())->getDataBySn($sn);
                } else {
                    return (new SnModel())->getDataBySn($sn);
                }
            } else {
                return toJson(array(
                    "status" => 0,
                    "data" => "序列号为空！"
                ));
            }
        }
    }
}