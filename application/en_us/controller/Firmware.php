<?php
/**
 * Created by PhpStorm.
 * User: jinxiu89
 * Date: 2019/7/20
 * Time: 16:39
 */
namespace app\en_us\controller;


use app\common\model\Service\ServiceCategory;
use think\App;
use app\common\model\Service\Firmware as model;
use app\en_us\validate\Firmware as validate;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;

/**
 * Class Firmware
 * @package app\en_us\controller
 */
class Firmware extends Base
{
    protected $model;
    protected $validate;

    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->model = new model();
        $this->validate = new validate();
        try {
            $cate = ServiceCategory::getTree($this->code,'Firmware');
            $this->assign('cate', $cate);
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
    }

    /***
     * @param string $order
     * @return mixed
     */
    public function index($order = 'desc')
    {
        try {
            $parent = [
                'seo_title' => lang('firmware'),
                'keywords' => lang('firmware'),
                'description' => lang('firmware'),
            ];
            $result = $this->model->getDataByLanguageId($this->language_id, 1, $order);
            return $this->fetch($this->template . '/firmware/index.html', [
                'data' => $result['data'],
                'count' => $result['count'],
                'parent' => $parent,
                'order' => $order
            ]);
        } catch (Exception $exception) {
            //todo::错误的页面渲染
            $this->error($exception->getMessage());
        }
    }

    /**
     * @param $title
     * @return mixed
     */
    public function details($title)
    {
        try {
            $result = $this->model->getDataByTitle($title);
            return $this->fetch($this->template . '/firmware/details.html', [
                'result' => $result
            ]);
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}