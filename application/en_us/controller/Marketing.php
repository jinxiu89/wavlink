<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/15
 * Time: 9:11
 */

namespace app\en_us\controller;
use app\common\model\Marketing as MarketingModel;

class Marketing extends Base
{
    public function index(){
        return "hello";
    }

    public function details($name=''){
        if(empty($name)){
            abort(500,"not found page");
        }
        $result = (new MarketingModel())->getDetailsByName($name)['content'];
        if(empty($result)){
            abort(404,"not found page");
        }
        return (new MarketingModel())->getDetailsByName($name)['content'];
    }
}