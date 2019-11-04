<?php


namespace app\en_us\controller;


use think\App;
use app\common\model\Firmware as model;
use app\en_us\validate\Firmware as validate;
use think\Exception;

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
            $result = $this->model->getDataByLanguageId($this->language_id, 1);
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