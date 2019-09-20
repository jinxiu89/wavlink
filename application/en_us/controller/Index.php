<?php
namespace app\en_us\controller;
use app\common\model\Images as ImagesModel;

/**
 * Class Index
 * @package app\en_us\controller
 *
 */
class Index extends Base
{
    public function index($type = "")
    {
        $imagesSlide = (new ImagesModel())->getImagesByFeatured($this->code,1);//幻灯片，首页第一屏
        $imagesMain = (new ImagesModel())->getImagesByFeatured($this->code,3);//主流产品推荐，首页第三屏
        $imagesNew = (new ImagesModel())->getImagesByFeatured($this->code, 4);//新品推荐位获取图片
        $imagesBest = (new ImagesModel())->getImagesByFeatured($this->code, 2);//热卖推荐，首页第二屏
        $imagesNotice =(new ImagesModel())->getImagesByFeatured($this->code, 6);//公告栏推荐位，导航上面的
        $this->assign('imagesSlide',$imagesSlide['data']);
        $this->assign('imagesMain',$imagesMain['data']);
        $this->assign("imagesNew", $imagesNew['data']);
        $this->assign("imagesBest", $imagesBest['data']);
        $this->assign('imagesNotice' ,$imagesNotice['data']);
        return $this->fetch($this->template.'/index/index.html');
    }

    public function build_html(){
        $this->index('index');
        return show(1,'','','','','更新首页缓存成功');
    }

    public function en(){
        $this->redirect(url('/en_us/index'));
    }

    public function product($id=''){
        $this->redirect(url('/en_us/index'));
    }
}