<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/4/28 16:26
 * @User: admin
 * @Current File : MediaImage.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\wavlink\controller\Media;


use app\lib\utils\cloud\ali;
use app\wavlink\controller\BaseAdmin;

/**
 * Class MediaImage
 * @package app\wavlink\controller\content
 */
class Image extends BaseAdmin
{
    /**
     * @return mixed
     */
    public function lists()
    {
        if ($this->request->isGet()) {
            $path = input('get.path', 'images', 'htmlspecialchars,trim') . '/';
            $items = ali::listObj('wavlink', $path);
            $this->assign('items', $items);
            $this->assign('path', $path);
            return $this->fetch();
        }
    }

    /**
     * @return bool|mixed
     */
    public function upload()
    {
        if ($this->request->isGet()) {
            $path = input('get.path', 'images', 'htmlspecialchars,trim');
            $this->assign('path', $path);
            return $this->fetch();
        }
        if ($this->request->isPost()) {
            $file = $this->request->file('file');
            $filePath=$file->getInfo('tmp_name');
            $key = input('get.path', 'images/', 'htmlspecialchars,trim') . $file->getInfo('name');

            $result = ali::putFile('',$file->getInfo('name'), $filePath);
            if ($result) {
                return $result;
            }
        }
    }
}