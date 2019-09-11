<?php
/**
 * Created by PhpStorm.
 * User: wavlink
 * Date: 2017/11/25
 * Time: 10:15
 */
namespace app\en_us\controller;
use app\common\model\About as AboutModel;
class About extends Base{
    /***
     * 前端关于我们的数据输出，美化URL，将原来的ID转换为title，
     * 语言判断，以便以后输出多种语言的页面，
     * 思路：必备一个$about参数，用于接受到底调用那一篇资料，通过模型查找符合要求的资料，另外如果找不到还需要报404错误，
     * 到时候还需要调试没一个错误，不允许出现系统异常。
     * @param $about
     * @return mixed
     */
    public function index($about){
        if ( empty($about) || !isset($about)) {
            abort(404);
        }
        //todo::不传参数时的问题还没有处理
        $result = AboutModel::getDetailsByUrlTitle($about,$this->code);
        if(!$result||empty($result)){
            abort(404);
        }
        $this->assign("result",$result);
        return $this->fetch($this->template.'/about/index.html', [
            'result'=>$result
            ]);
    }
}