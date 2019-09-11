<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/5/27
 * Time: 17:22
 */
namespace app\common\helper;

class Search
{
    public static function search($model,$map,$order,$page=12){
        //公共查询函数
        $data   = model($model)->where($map)->order($order)->paginate($page);
        $counts = model($model)->where($map)->count();
        if($data){
            $result=array(
                'data'=>$data,
                'count'=>$counts,
            );
            return $result;
        }else{
            return '';
        }
    }
}