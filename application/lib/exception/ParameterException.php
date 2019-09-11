<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/1/2
 * Time: 16:57
 */
namespace app\lib\exception;

class ParameterException extends BaseException {
    public $code = 200;
    public $msg = '参数错误';
    public $errorCode = 10000;
}