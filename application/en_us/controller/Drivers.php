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
        $data = (new driversCategory())->getCategoryByLanguage($status = 1, $this->language_id);
        $level = Category::toLevel($data->toArray(), '&emsp;&emsp;');
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
    public function index($order = 'desc', $page = 1)
    {
        //获取所有驱动下载列表
        $result = $this->service->getDriversByLanguage($this->language_id, $order, $page);
        return $this->fetch($this->template . '/drivers/index.html', [
            'data' => $result['data'],
            'count' => $result['count'],
            'category_title' => '',
            'order' => $order
        ]);
    }


    //获取选择分类下的驱动列表

    /**
     * @param string $category
     * @param $order
     * @param int $page
     * @return Redirect|View
     */
    public function category($category = "", $order, $page = 1)
    {
        if (empty($category) || !isset($category)) {
            abort(404);
        }
        if ($category == 'all') {
            return redirect(url('/' . $this->code . '/drivers', ['order' => $order]), [], 200);
        }
        //获取选择的子分类信息
        $parent = $this->service->getCategoryID($category, $this->language_id);
        if (empty($parent)) {
            abort(404);
        } else {
            //获取选择的分类下的驱动列表
            $result = $this->service->getDriversByCategoryIds($this->language_id,$this->code,$category, $parent['categoryID'], $order,$page);
            return view($this->template . '/drivers/index.html', [
                'data' => $result['data'],
                'parent' => $parent['category'],
                'count' => $result['count'],
                'category_title' => $parent['category']['title'],
                'order' => $order
            ]);
        }
    }

    /**
     * @param string $drivers
     * @return mixed
     */
    public function details($drivers = "")
    {
        if (!isset($drivers) || empty($drivers)) {
            abort(404);
        }
        try {
            $result = DriversModel::getDetailsByUrlTitle($this->code, $drivers);
            $result_models = $result["models"];
            $models = explode(",", $result_models);
            if (!empty($result)) {
                return $this->fetch($this->template . '/drivers/details.html', [
                    'result' => $result,
                    'models' => $models
                ]);
            } else {
                abort(404);
            }
        } catch (DataNotFoundException $e) {
            $this->error($e->getMessage());
        } catch (ModelNotFoundException $e) {
            $this->error($e->getMessage());
        } catch (DbException $e) {
            $this->error($e->getMessage());
        }
    }
}