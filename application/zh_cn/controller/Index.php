<?php

namespace app\zh_cn\controller;

use app\common\model\Images as ImagesModel;

class Index extends Base
{
    public function index($type = " ")
    {
        $imagesNew = (new ImagesModel())->getImagesByFeatured($this->code, 4, 1);//新品推荐位获取图片
        $imagesBest = (new ImagesModel())->getImagesByFeatured($this->code, 2, 3);//热卖推荐，首页第二屏
        $imagesSlide = (new ImagesModel())->getImagesByFeatured($this->code, 1, 4);//幻灯片，首页第一屏
        $imagesMain = (new ImagesModel())->getImagesByFeatured($this->code, 3, 1);//主流产品推荐，首页第三屏
        $imagesNotice = (new ImagesModel())->getImagesByFeatured($this->code, 6, 1);//公告栏推荐位，导航上面的
        $this->assign("imagesNew", $imagesNew['data']);
        $this->assign("imagesBest", $imagesBest['data']);
        $this->assign('imagesSlide', $imagesSlide['data']);
        $this->assign('imagesMain', $imagesMain['data']);
        $this->assign('imagesNotice', $imagesNotice['data']);
        return $this->fetch($this->template . '/index/index.html');
    }

    public function build_html()
    {
        $this->index('index');
        return show(1, ' ', '更新首页缓存成功');
    }
}