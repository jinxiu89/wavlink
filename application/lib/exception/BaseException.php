<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/1/3
 * Time: 11:34
 */
namespace app\lib\exception;
use think\Exception;
class BaseException extends Exception
{
    // HTTP 状态码 404,200
    public $code = 400;

    // 错误具体信息
    public $msg = '参数错误';

    // 自定义的错误码
    public $errorCode = 10000;
    public function __construct($message = "", $code = 0, \Exception $previous = null) {
    }
}