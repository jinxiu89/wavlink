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

namespace app\wavlink\controller\content;


use app\wavlink\controller\BaseAdmin;

/**
 * Class MediaImage
 * @package app\wavlink\controller\content
 */
class MediaImage extends BaseAdmin
{
    /**
     * @return mixed
     */
    public function lists()
    {
        if ($this->request->isGet()) {
            return $this->fetch();
        }
    }
}