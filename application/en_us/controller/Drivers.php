<?php
/**
 * Created by PhpStorm.
 * User: wavlink
 * Date: 2017/11/25
 * Time: 14:24
 */

namespace app\en_us\controller;

use app\common\helper\Category;
use app\common\model\Service\Drivers as DriversModel;
use app\common\service\en_us\Drivers as DriverService;
use app\wavlink\service\service\driversCategory;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\response\Redirect;
use think\response\View;


/***
 * Class Drivers
 * @package app\en_us\controller
 */
class Drivers extends Base
{
    public $service;
    public $cat;

    public function initialize()
    {
        parent::initialize();
    }

    /**
     * Drivers constructor.
     * @param App|null $app
     */
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->service = new DriverService();//驱动服务层
        $data = (new driversCategory())->getCategoryByLanguage($status = 1,2);
        $level = Category::toLayer($data->toArray(), 'child');
        $this->assign('cate', $level);
        unset($data);
    }
    /***
     * $this->code 为 当前的模块名，即在上面_initialize(初始化中)赋予的
     *
     */
    //驱动下载首页
    /***
     * @param string $order
     * @param int $page
     * @return mixed
     */
    public function index($order = 'desc')
    {
        //获取所有驱动下载列表
        $result = $this->service->getDataByCategory(2, $order, $category = '');
        $data = Category::toLayer($result, 'child');
        return $this->fetch($this->template . '/drivers/index.html', ['category_title' => '', 'data' => $data]);
    }


    //获取选择分类下的驱动列表

    /**
     * @param string $category
     * @param string $order
     * @return Redirect|View
     */
    public function category($category = "", $order = 'desc')
    {
        $result = $this->service->getDataByCategory(2, $order, $category);
        return $this->fetch($this->template . '/drivers/category.html', [
            'category_title' => $result[0]['url_title'], 'title'=>$result[0]['title'],'data' => $result]);
        
    }

    /**
     * @param $detail
     * @return mixed
     */
    public function detail($detail)
    {
        if ($this->request->isGet()) {
            $data = $this->service->getDataByUrlTitle($detail);
            return $this->fetch($this->template . '/drivers/download.html', ['data' => $data]);
        }
        exit;
    }
}