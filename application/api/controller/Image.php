<?php
namespace app\api\controller;

use think\Controller;
use think\Request;

class Image extends Controller
{
    public function upload() {
        $file = Request::instance()->file('file');
        //给定一个目录
        $info = $file->move('upload', '', false);
        if ($info && $info->getPathname()) {
            //'/' 这个斜杠对应的是网站入口文件,public
            return show(1, 'success', '/' . $info->getPathname());
        } else {
            return show(0, 'upload error');
        }
    }

    public function editorUpload() {
        $file = Request::instance()->file('imgFile');
        //给定一个目录
        $info = $file->move('ueditor');
        if ($info && $info->getPathname()) {
            //'/' 这个斜杠对应的是网站入口文件,public
            $data = array(
                'error' => 0,
                'url' => '/' . $info->getPathname(),
            );
            print_r(json_encode($data));
        } else {
            return show(1, 'upload error', '','','',$info->getError());
        }
    }

    public function imageAddress() {
        $data = [
            'path' => '/ueditor',
            'dir' => 'ueditor'
        ];

        return json_encode($data);
    }
}
