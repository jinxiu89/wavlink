<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/10/12
 * Time: 11:32
 *
 */
/**
 * @return mixed
 * 从浏览器得到语言
 */
function autoGetLang(){
    //拿到浏览器的语言，初始化语言项
    $AcceptLanguage = \think\Request::instance()->header();
    if (empty($AcceptLanguage['accept-language'])){
        return '';
    }
    $lang_code = $AcceptLanguage['accept-language'];
    $code = explode(',',$lang_code);
    //在extra 里配置各国语言代码对应相应的模块
    return $code[0];
}
