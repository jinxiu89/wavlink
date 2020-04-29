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
            $prefix = !empty(input('get.prefix', '', 'htmlspecialchars,trim')) ? input('get.prefix', '', 'htmlspecialchars,trim') . '/' : 'videos/';
            $items = ali::listObj('wavlink', $prefix);
            print_r($items);exit;
            $key = array_search($prefix, $items);
            array_splice($items, $key, 1);
            $data=[];
            foreach ($items as $item){
                $temp=explode('/',$item);
                if(empty(end($temp))) $data['type']='dir';

                $data[]=end($temp);
            }
            $this->assign('items',$items);
            return $this->fetch();
        }
    }
}