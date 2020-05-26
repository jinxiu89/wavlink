<?php

namespace app\en_us\controller;

use app\common\helper\Category;
use app\common\model\Service\Video as VideoModel;
use app\common\model\Service\ServiceCategory as ServiceCategoryModel;
use app\wavlink\service\service\driversCategory as service;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\facade\Request;

class  Video extends Base
{
    protected $model;

    /**
     * Video constructor.
     * @param App|null $app
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->model = new VideoModel();
        try {
            $cate = ServiceCategoryModel::getTree($this->code, 'Videos');
            $this->assign('videos', $cate);
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
        $data = (new service())->getDataByLanguageId($status = 1, $this->language_id);
        $level = Category::toLevel($data['data']->toArray()['data'], '&emsp;&emsp;');
        $this->assign('cate', $level);
    }

    /**
     * @return mixed
     */
    public function index()
    {
        if (Request::isGet()) {
            try {
                $result = $this->model->getVideoByLanguage($this->code);
                return $this->fetch($this->template . '/video/index.html', ['data' => $result['data'], 'count' => $result['count'], 'name' => '',]);
            } catch (\Exception $exception) {
                $this->error($exception->getMessage());
            }
        }
        if (Request::isPost()) {
            try {
                exception('拒绝访问', 403);
            } catch (\Exception $e) {
            }
        }
    }

    /**
     * @param string $url_title
     * @param string $order
     * @return \think\response\View
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function category($url_title = "", $order = 'listorder desc')
    {
        if (empty($url_title) && !isset($url_title)) {
            abort(404);
        }
        if ($url_title == 'all') {
            return redirect(url('/' . $this->code . '/video'), [], 200);
        }
        $parent = ServiceCategoryModel::getCategoryIdByName($this->code, $url_title);
        $field = 'id,name,url_title,image,urlabroad,urlchina';
        if ($parent['has_child']) {
            $categorys = ServiceCategoryModel::getCategoryByPath($parent['path'], $parent['id']);
            $categorys[] = $parent['id'];
            $result = VideoModel::getDataByPath($categorys, $field, $order);
            if ($result['status'] == $this) {
                $this->assign('data', $result['data']);
                $this->assign('count', $result['count']);
                $this->assign('name', $parent['name']);
            } else {
                abort(404);
            }
        } else {
            $result = VideoModel::getDataByPath([$parent['id']], $field, $order);
            if ($result['status'] == true) {
                $this->assign('data', $result['data']);
                $this->assign('count', $result['count']);
                $this->assign('name', $parent['name']);
            } else {
                abort(404);
            }
        }
        return $this->fetch($this->template . '/video/index.html');
    }

    //视频详情页

    /***
     * @param string $video
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     *
     */
    public function details($video = "")
    {
//        if (empty($video) || !isset($video)) {
//            abort(404);
//        }
//        $result = VideoModel::getDetailsByUrlTitle($video, $this->code);
//        if (!empty($result)) {
//            return $this->fetch($this->template . '/video/detail.html', [
//                'result' => $result
//            ]);
//        } else {
//            abort(404);
//        }
    }
}