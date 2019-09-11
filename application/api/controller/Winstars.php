<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/5/29
 * Time: 11:28
 */

namespace app\api\controller;

use app\common\model\BigData;
use think\Controller;
use think\Db;
use think\Request;
header('content-type:application:json;charset=utf8');
header('Access-Control-Allow-Origin:*');   // 指定允许其他域名访问
header('Access-Control-Allow-Headers:x-requested-with,content-type');// 响应头设置
class Winstars extends Controller
{
    public function index(){
//        $data = Request::instance()->post();
//        print_r($data);
        return "hello";
    }
    public function add() {
        $data = Request::instance()->param();
        $email = BigData::all([
            'email' => $data['email']
        ]);
        if (count($email) >= 2){
            return json(array(
                'status'=>0,
                'msg'=>"Already existed"
            ),200);
        }
        try{
            if(!empty($data)){
                if(Db::table('winstars')->insert($data)){
                    return json(array(
                        "status"=>1,
                        "msg"=>"ok"
                    ),200);
                }else{
                    return json(array(
                        "status"=>0,
                        "msg"=>"is error"
                    ),200);
                }
            }else{
                return json(array(
                    "status"=>0,
                    "msg"=>"not found data"
                ),404);
            }
        }catch (\Exception $e){
            return json(array(
                "status" => 0,
                "msg"   => 'not found'
            ),200);
        }


    }
}