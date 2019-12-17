<?php

namespace app\en_us\controller;

use app\common\helper\CheckIP;
use app\common\model\Article;
use app\common\model\Images as ImagesModel;
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
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
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
     * @param string $type
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws Exception
     *
     */
    public function index($type = "")
    {
        $ImageModel = new ImagesModel();
        $Notice = $ImageModel->getImagesByFeatured($this->code, 6);//公告栏推荐位，导航上面的
        $swiper = $ImageModel->getImagesByFeatured($this->code, 1);//幻灯片，首页第一屏
        $hot = $ImageModel->getImagesByFeatured($this->code, 2);//热卖推荐，首页第二屏
        $showcase = $ImageModel->getImagesByFeatured($this->code, 3);//主流产品推荐，首页第三屏
        //新闻调用
        $News=(new Article())->getLastNew($this->code);
        $imagesNew = (new ImagesModel())->getImagesByFeatured($this->code, 4);//新品推荐位获取图片

        $this->assign('Notice', $Notice['data']);
        $this->assign('swiper', $swiper['data']);
        $this->assign("hot", $hot['data']);
        $this->assign('showcase', $showcase['data']);
        $this->assign('News',$News);
        $this->assign("imagesNew", $imagesNew['data']);
        return $this->fetch($this->template . '/index/index.html');
    }

    public function build_html()
    {
        $this->index('index');
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