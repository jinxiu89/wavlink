<?php
 namespace app\customer\controller;
 use app\common\model\Country;

 /**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/3/13
 * Time: 16:28
 */
class Index extends Base
{

    public function index(){
        $country=(new Country())->field('country_id,name')->select();
        return $this->fetch('',[
            'country'=>$country
        ]);
    }
    public function add($id = ''){
        return $id;
    }

}