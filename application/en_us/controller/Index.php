<?php

namespace app\en_us\controller;

use app\common\helper\CheckIP;
use app\common\model\Content\Article;
use app\common\model\Content\Images as ImagesModel;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;

/**
 * Class Index
 * @package app\en_us\controller
 *
 */
class Index extends Base
{
    /**
     * Index constructor.
     * @param App|null $app
     */
    public function __construct(App $app = null)
    {
        parent::__construct($app);
    }

    /**
     *
     */
    public function initialize()
    {
        parent::initialize();
    }

    /***
     * @return mixed
     */
    public function index()
    {
        $ImageModel = new ImagesModel();
        $Notice = $ImageModel->getImagesByFeatured($this->language_id, 6);//公告栏推荐位，导航上面的
        $layer = $ImageModel->getImagesByFeatured($this->language_id, 7);//首页强推层，一般用于用户触达，活动触达等
        $swiper = $ImageModel->getImagesByFeatured($this->language_id, 1);//幻灯片，首页第一屏
        $hot = $ImageModel->getImagesByFeatured($this->language_id, 2);//热卖推荐，首页第二屏
        $showcase = $ImageModel->getImagesByFeatured($this->language_id, 3);//主流产品推荐，首页第三屏
        $imagesNew = (new ImagesModel())->getImagesByFeatured($this->language_id, 4);//新品推荐位获取图片
        unset($ImageModel);
        //新闻调用
        $News=(new Article())->getLastNew($this->language_id);
        $this->assign('Notice', $Notice['data']);
        $this->assign('layer',$layer['data']);
        $this->assign('swiper', $swiper['data']);
        $this->assign("hot", $hot['data']);
        $this->assign('showcase', $showcase['data']);
        $this->assign('News',$News);
        $this->assign("imagesNew", $imagesNew['data']);
        return $this->fetch($this->template . '/index/index.html');
    }

    public function build_html()
    {
        $this->index();
        return show(1, '', '', '', '', '更新首页缓存成功');
    }

    public function en()
    {
        $this->redirect(url('/en_us/index'));
    }

    public function product($id = '')
    {
        $this->redirect(url('/en_us/index'));
    }
}