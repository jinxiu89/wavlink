<?php
/**
 * Created by PhpStorm.
 * User: wavlink
 * Date: 2017/11/25
 * Time: 14:24
 */

namespace app\en_us\controller;

use app\common\helper\Category;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\exception\HttpException;
use app\common\model\Service\Drivers as DriversModel;
use app\common\model\Service\ServiceCategory as ServiceCategoryModel;
use app\common\model\Service\DriversCategory;
use app\wavlink\service\service\driversCategory as service;
use think\Request;
use think\response\View;


/***
 * Class Drivers
 * @package app\en_us\controller
 */
class Drivers extends Base
{
    /**
     * Drivers constructor.
     * @param App|null $app
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        try {
            $data = (new service())->getDataByLanguageId($status=1,$this->language_id);
            $level = Category::toLevel($data['data']->toArray()['data'], '&emsp;&emsp;');
            $this->assign('cate', $level);
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
    }
    /***
     * $this->code 为 当前的模块名，即在上面_initialize(初始化中)赋予的
     *
     */
    //驱动下载首页
    /***
     * @param string $order
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Exception
     * @throws ModelNotFoundException
     */
    public function index($order = 'desc')
    {
        //获取Drivers分类信息
        $parent = ServiceCategoryModel::getTopCategory($this->language_id, 'Drivers');
        //获取所有驱动下载列表
        $result = (new DriversModel())->getDriversByLanguage($this->language_id, $order);
        return $this->fetch($this->template . '/drivers/index.html', [
            'data' => $result['data'],
            'parent' => $parent,
            'count' => $result['count'],
            'category_title' => '',
            'order' => $order
        ]);
    }


    //获取选择分类下的驱动列表

    /**
     * @param string $category
     * @param $order
     * @return \think\response\Redirect|View
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function category($category = "", $order)
    {
        if (empty($category) || !isset($category)) {
            abort(404);
        }
        if ($category == 'all') {
            return redirect(url('/' . $this->code . '/drivers',['order'=>$order]), [], 200);
        }
        //获取选择的子分类信息
        $parent = (new service())->getCategoryID($category,$this->language_id);
        if (empty($parent)) {
            abort(404);
        } else {
            //获取选择的分类下的驱动列表
            $result = (new DriversModel())->getDriversByCategoryIds($this->language_id,$parent['categoryID'], $order);
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