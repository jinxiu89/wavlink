<?php

function arr_unique($arr2d){
    foreach ($arr2d as $k=>$v) {
        $v=join(',',$v);
        $temp[]=$v;
    }
    if($temp){
        $temp=array_unique($temp);
        foreach ($temp as $k=>$v) {
            $temp[$k]=explode(',', $v);
        }

        return $temp;
    }

}
////获取语言
//function getLanguage($id)
//{
//    $map= intval($id);
//    $data = \app\common\model\Language::get($map);
//    return $data['name'];
//}

///***
// * 返回错误信息
// * @param $status 状态值
// * @param string|返回的消息 $message 返回的消息
// * @param array $data 数据
// * @return array 本处修改是因为固件下载的提交 原来注销的这部分不知道在哪里有用过！
// * 本处修改是因为固件下载的提交 原来注销的这部分不知道在哪里有用过！
// * @internal param $title
// * @internal param $btn
// * @internal param string $url 返回跳转url
// */
///**
// * 获取和设置语言定义(不区分大小写)
// * @param string|array $name 语言变量
// * @param mixed $value 语言值或者变量
// * @return mixed
// */
//function L($name=null, $value=null) {
//    static $_lang = array();
//    // 空参数返回所有定义
//    if (empty($name))
//        return $_lang;
//    // 判断语言获取(或设置)
//    // 若不存在,直接返回全大写$name
//    if (is_string($name)) {
//        $name   =   strtoupper($name);
//        if (is_null($value)){
//            return isset($_lang[$name]) ? $_lang[$name] : $name;
//        }elseif(is_array($value)){
//            // 支持变量
//            $replace = array_keys($value);
//            foreach($replace as &$v){
//                $v = '{$'.$v.'}';
//            }
//            return str_replace($replace,$value,isset($_lang[$name]) ? $_lang[$name] : $name);
//        }
//        $_lang[$name] = $value; // 语言定义
//        return null;
//    }
//    // 批量定义
//    if (is_array($name))
//        $_lang = array_merge($_lang, array_change_key_case($name, CASE_UPPER));
//    return null;
//}
//function show($status, $message = '', $data = [])
//{
//    $res = array(
//        'status' => intval($status),
//        'message' => $message,
//        'data' => $data,
//    );
//    exit(json_encode($res));
//}

