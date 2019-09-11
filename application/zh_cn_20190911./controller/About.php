<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/3/7
 * Time: 13:58
 */

namespace app\zh_cn\controller;

use app\common\model\About as AboutModel;

class About extends Base
{
    /***
     */
    public function index($about)
    {
        if (empty($about) || !isset($about)) {
            abort(404);
        }
        //todo::不传参数时的问题还没有处理
        $result = AboutModel::getDetailsByUrlTitle($about, $this->code);
        if (!$result || empty($result)) {
            abort(404);
        }
        $this->assign("result", $result);
        return $this->fetch($this->template. '/about/index.html', ['result' => $result]);
    }
}