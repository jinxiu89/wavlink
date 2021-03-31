<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2021/3/29 9:55
 * @User: kevin
 * @Current File : Support.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\en_us\controller;

use app\common\helper\Category;
use app\common\model\Service\Firmware as model;
use app\common\model\Service\ServiceCategory;
use app\en_us\validate\Firmware as validate;
use app\wavlink\service\service\driversCategory as service;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use app\common\service\en_us\Support as SupportService;
/**
 * Class Support
 * @package app\en_us\controller
 * 说明书支持相关的访问控制器
 *
 */
class Support extends Base
{
    protected $code;

    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->model = new model();
        $this->validate = new validate();
        //todo:: 这里在真个无极分类做好后会进入一个休整，将一些分类的东西聚合起来 再传递到前端
        try {
            $cate = ServiceCategory::getTree($this->code, 'Firmware');
            $this->assign('fw', $cate);
        } catch (DataNotFoundException | ModelNotFoundException | DbException $e) {
        }
        $data = (new service())->getDataByLanguageId($status = 1, $this->language_id);
        $level = Category::toLevel($data['data']->toArray()['data'], '&emsp;&emsp;');
        $this->assign('cate', $level);
    }

    /**
     * @return mixed
     *
     */
    public function index()
    {

    }

    /**
     * @param string $model 该产品的标准型号
     * @return mixed
     */
    public function model(string $model)
    {
        if ($this->request->isGet()) {
            //todo:: 产品详情 给到 页面标题上
            //todo:: 说明书
            $manual=(new SupportService)->getManualByModel($this->language_id,$model);
            //todo:: 驱动
            $driver=(new SupportService())->getDriverByModel($this->language_id,$model);
            print_r($manual);
            print_r($driver);
            $result['name']=$model;
            $result['keywords']='';
            $result['description']='';
            //todo:: 固件
            //todo::常见问题
            //todo:: 视频
            return $this->fetch($this->template . '/support/index.html',[
                'manual'=>$manual,
                'driver'=>$driver,
                'result' =>$result
            ]);
        }
    }
}