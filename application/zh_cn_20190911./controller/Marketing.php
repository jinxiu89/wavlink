<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/15
 * Time: 9:11
 */

namespace app\zh_cn\controller;
use app\common\model\Marketing as MarketingModel;

class Marketing extends Base
{
    public function details($name=''){
        if(empty($name)){
            abort(404);
        }
        $result = (new MarketingModel())->getDetailsByName($name)['content'];
        if(!empty($result)){
            return $result;
        }else{
            abort(404);
        }
    }
}