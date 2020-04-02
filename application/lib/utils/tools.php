<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/3/31 9:51
 * @User: admin
 * @Current File : tools.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\lib\utils;

/**
 * Class tools
 * @package app\lib\utils
 * 小工具：常包含一些通用的函数，例如生成一组随机数（字母+数字的）
 */
class tools
{
    /**
     * GetStr 用于生成一个指定长度的字母数字的字符串
     * @param $len
     * @return string
     */
    public static function GetStr($len){
        $chars_array = [
            "0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z",
        ];
        $charsLen = count($chars_array) - 1;
        $str = "";
        for ($i = 0; $i < $len; $i++) {
            $str .= $chars_array[mt_rand(0, $charsLen)];
        }
        return $str;
    }

    /**
     * @param $len
     * @return string
     */
    public static function GetIntStr($len){
        $chars_array = [
            "0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
        ];
        $charsLen = count($chars_array) - 1;
        $str = "";
        for ($i = 0; $i < $len; $i++) {
            $str .= $chars_array[mt_rand(0, $charsLen)];
        }
        return $str;
    }
}