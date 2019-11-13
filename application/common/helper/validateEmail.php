<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/5/27
 * Time: 17:22
 */

namespace app\common\helper;
/**
 * Class validateEmail
 * @package app\common\helper
 */
class validateEmail
{
    /**
     * @param $email == jinxiu89@163.com 即邮箱地址
     * @return bool
     * 验证一个邮箱主机是不是存在
     */
    public function checkMX($email)
    {
        if (filter_var($email) === false) {
            return false;
        }
        if (checkdnsrr(array_pop(explode("@", $email)), "MX") === false) {
            return false;
        }
        return true;
    }

    /**
     * @param $email
     */
    public function checkEmail($email){
        
    }

}