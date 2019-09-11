<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/1/3
 * Time: 11:34
 */
namespace app\lib\exception;

use app\index\controller\AutomaticJump;
use Symfony\Component\Yaml\Tests\A;
use think\exception\Handle;
use think\Request;
use think\Log;
use think\Response;

class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $errorCode;

    public function render(\Exception $e) {
        //如果是自定义的异常
        if($e instanceof BaseException){
            $this->code = $e->code;
            $this->msg  = $e->msg;
            $this->errorCode = $e->errorCode;
        }else{

//            if (substr(Request::instance()->url(),1,2) === 'en' && $e->code == 500 ){
//                return redirect(url('en_us/index'));
//            }
//            if (substr(Request::instance()->url(),1,2) === 'cn' && $this->code == 500){
//                return redirect(url('zh_cn/index'));
//            }
            $this->recordErrorLog($e);
            return parent::render($e);
        }
        $request = Request::instance();
        $result = [
            'msg' => $this->msg,
            'error_code' => $this->errorCode,
            'request_url' => $request->url(),
        ];
        return show(0,'','','','',$this->msg);
    }
    private function recordErrorLog(\Exception $e)
    {
        Log::init(
            [
                'type' => 'File',
                'path' => LOG_PATH,
                'level' => ['error']
            ]);
        Log::record($e->getMessage(), 'error');
    }
}