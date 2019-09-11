<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/9/26
 * Time: 14:23
 */

namespace app\en_us\controller;


use app\common\model\OldSn;
use app\common\model\Sn as SnModel;

class Sn extends Base
{
    public function index()
    {
        return $this->fetch($this->template.'/sn/index.html');
    }
}