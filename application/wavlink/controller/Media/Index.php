<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/4/29 13:55
 * @User: admin
 * @Current File : Index.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\wavlink\controller\Media;


use app\wavlink\controller\BaseAdmin;

/**
 * Class Index
 * @package app\wavlink\controller\Media
 * 资源管理首页
 */
class Index extends BaseAdmin
{
    /**
     * @return mixed
     *
     */
    public function index(){
        return $this->fetch();
    }
}