<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/4/29 13:40
 * @User: admin
 * @Current File : Drivers.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\wavlink\controller\Media;


use app\lib\utils\cloud\ali;
use app\wavlink\controller\BaseAdmin;
use think\facade\Config;

class Driver extends BaseAdmin
{
    public function lists(){
        if ($this->request->isGet()) {
            $path = input('get.path', 'drivers/', 'htmlspecialchars,trim');
            $items = ali::listObj('wavlink', $path);
            $baseUrl=Config::get('alicloud.oss.baseUrl'); //传递到前端 防止换来换去，全部都要手撸
            $this->assign('baseUrl',$baseUrl);
            $this->assign('items', $items);
            $this->assign('path', $path);
            return $this->fetch();
        }
    }
    /**
     * createFolder
     * @return mixed
     */
    public function createFolder()
    {
        if ($this->request->isGet()) {
            $path = input('get.path', 'drivers', 'htmlspecialchars,trim');
            $this->assign('path', $path);
            return $this->fetch();
        }
        if ($this->request->isPost()) {
            $post = input('post.', '', 'htmlspecialchars,trim');
            $key = input('get.path', 'drivers/', 'htmlspecialchars,trim') . $post['folder'];
            //todo::阿里云和亚马逊云合体在这里分分支
            $result = ali::mkdir($key);
            if($result == 200){
                return show(true, '创建成功', '', '', '', '创建成功');
            }
            return show(false, '创建失败', '', '', '', '创建失败');
        }
    }
    /**
     * @return bool|mixed
     */
    public function upload()
    {
        if ($this->request->isGet()) {
            $path = input('get.path', 'drivers', 'htmlspecialchars,trim');
            $this->assign('path', $path);
            return $this->fetch();
        }
        if ($this->request->isPost()) {
            $file = $this->request->file('file');
            $filePath = $file->getInfo('tmp_name');
            $key = input('get.path', 'drivers/', 'htmlspecialchars,trim') . $file->getInfo('name');
            ali::putFile($key, $filePath);
        }
    }

    public function del(){
        if($this->request->isPost()){
            $key=input('get.key','','htmlspecialchars,trim');
            $result=ali::delFile($key);
            if($result == 204) return show(true, '删除成功', '', '', '', '删除成功');
            return show(false,'删除失败','','','','删除失败');
        }
    }
}