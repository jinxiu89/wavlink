<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/1/2
 * Time: 17:02
 */
namespace app\common\validate;
use app\lib\exception\ParameterException;
use think\facade\Request;
use think\Validate;

/***
 * Class BaseValidate
 * @package app\common\validate
 */
class BaseValidate extends Validate {
    public function goCheck($scene=''){
        // 获取http传入的参数
        // 对这些参数做检验
        $request = Request::instance();
        $params = $request->param();
        $result = $this->scene($scene)->check($params);
        if(!$result){
            $e = new ParameterException();
            $e->msg = $this->error;
            throw $e;
        }else{
            return true;
        }
    }
}