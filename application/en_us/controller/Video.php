<?php

namespace app\en_us\controller;

use app\common\model\Video as VideoModel;
use app\common\model\ServiceCategory as ServiceCategoryModel;
use think\App;
use think\facade\Request;
use think\exception;

class  Video extends Base
{
    protected $model;

    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->model = new VideoModel();
    }

    /**
     * @return mixed
     */
    public function index()
    {
        if (Request::isGet()) {
            try {
                $result = $this->model->getVideoByLanguage($this->code);
                return $this->fetch($this->template . '/video/index.html', ['data' => $result['data'], 'count' => $result['count']]);
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