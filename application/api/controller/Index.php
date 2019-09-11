<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/1/5
 * Time: 13:34
 */

namespace app\api\controller;


use think\Controller;
header('content-type:application:json;charset=utf8');
header('Access-Control-Allow-Origin:*');   // 指定允许其他域名访问
header('Access-Control-Allow-Headers:x-requested-with,content-type');// 响应头设置
class Index extends  Controller
{
    public function index(){
        $data=array(
            "name"=>'thinkphp',
            "url"=>"www.wavlink.com",
            "author"=>"linguibing"
        );
        return json(array(
            "data"=>$data,
            "code"=>200,
            "message"=>"ok"
        ));
    }
}