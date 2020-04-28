<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/3/26 15:48
 * @User: admin
 * @Current File : User.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

/**
 * @return mixed
 * 从浏览器得到语言
 */
/**
 * 根据数据库记录转换性别
 * @param $gender
 * @return string
 */
function getGender($gender){
    if($gender == 1){
        return lang('Male');
    }elseif ($gender == 2){
        return lang('Female');
    }
    return lang('Secret');
}

/**
 * @param $email
 * @return string
 * 如果前台（info）页邮箱没有填的话，默认给他N/A
 */
function getEmail($email){
    if($email == 1){
        return '';
    }
    return $email;
}
function getPhone($phone){
    if($phone == 1){
        return '';
    }
    return $phone;
}

/**
 * 用户端的国家输出并翻译
 * @param $id
 * @return mixed
 */
function getCountry($id){
    try{
        $country=(new app\common\model\Country())->field('country_id,name')->get(['country_id'=>$id]);
        return $country['name'];
    }catch (Exception $exception){
        return 'N/A';
    }

}