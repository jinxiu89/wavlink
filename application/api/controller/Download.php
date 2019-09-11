<?php
namespace app\api\controller;
use think\Controller;
use think\Request;
use think\File;
Class Download extends Controller
{
    public function upload(Request $request){
        $file = $request::instance()->file('file');
        $info = $file->validate(['size'=>50000000])->move('download','');
        if ($info && $info->getPathname()){
            //'/' 这个斜杠对应的是网站入口文件,public
            return show(1,'success','/'.$info->getPathname());
        }
        return show(0,'upload error',validate()->getError());
    }
}
