<?php
function arr_unique($arr2d)
{
    foreach ($arr2d as $k => $v) {
        $v = join(',', $v);
        $temp[] = $v;
    }
    if ($temp) {
        $temp = array_unique($temp);
        foreach ($temp as $k => $v) {
            $temp[$k] = explode(',', $v);
        }
        return $temp;
    }
}

/**
 * @return mixed
 * 从浏览器得到语言
 */
function Get_Lang()
{
    $AcceptLanguage = \think\Request::instance()->header();
    $lang_code = $AcceptLanguage['accept-language'];
    $code = explode(',', $lang_code);
    return $code[0];
}

function getLang()
{
    //如果有模块设置的语言session.就取session里设置的模块名做语言
    if (\think\Session::has('langModel', 'Customer')) {
        $lang = \think\Session::get('langModel', 'Customer');
    } else {
        //如果没有模块设置的语言session,就表明他是异常登录，拿到浏览器的语言，初始化语言项
        $res = Get_Lang();
        $lang = \think\Config::get($res);
    }
    return $lang;
}


