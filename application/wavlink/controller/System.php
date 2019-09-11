<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:46
 */
namespace app\wavlink\controller;
use app\wavlink\validate\System as SystemValidate;
use app\common\model\System as SystemModel;
use think\Cookie;

Class System extends BaseAdmin
{
    public function index()
    {
        return $this->fetch();
    }
    public function system(){
        $system = config('system.system');
        if(Cookie::has('systemPage')){
            $page = Cookie::get('systemPage');
        }else{
            $page = $system['page'];
        }
        return $this->fetch('',[
            'system' => $system,
            'page'  => $page
        ]);
    }
    public function systemSave(){
        (new SystemValidate())->goCheck();
        $data = input('post.');
        $res = (new SystemModel())->saveSystem($data);
//        print_r($res);exit();
        if ($res) {
            return show(1, '', '', '', '', '修改成功');
        } else {
            return show(1, '', '', '', '', '修改失败');
        }
    }
}