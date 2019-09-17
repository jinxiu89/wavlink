<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:37
 * 下载中心
 */
namespace app\wavlink\controller;
use think\Request;
use app\common\model\Video as VideoModel;
use app\common\model\ServiceCategory as ServiceCategoryModel;
use app\wavlink\validate\Video as VideoValidate;
use app\wavlink\validate\UrlTitleMustBeOnly;
Class Video extends BaseAdmin
{
    public function index() {

        $video = VideoModel::getDataByStatus(1, $this->currentLanguage['id']);
        return $this->fetch('', [
            'video' => $video['data'],
            'counts' => $video['count'],
            'language_id' => $this->currentLanguage['id']
        ]);
    }

    /**
     * 回收站页面
     */
    public function video_recycle() {
        $video = VideoModel::getDataByStatus(-1);
        return $this->fetch('', [
            'video' => $video['data'],
            'counts' => $video['count'],


        ]);
    }

    /**
     * @return mixed
     * 上传视频页面
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function add() {
        //获取服务管理的当前视频分类
        $categorys = ServiceCategoryModel::getSecondCategory($this->currentLanguage['id']);
        return $this->fetch('', [
            'categorys' => $categorys,
            'language_id' => $this->currentLanguage['id'],
        ]);
    }

    /**
     * 保存操作
     * @param Request $request
     * @return array
     * @throws \app\lib\exception\ParameterException
     */
    public function save(Request $request) {
        if (request()->isAjax()) {
            $data = $request::instance()->post();
            (new VideoValidate())->goCheck();
            (new UrlTitleMustBeOnly())->goCheck();
            if (!empty($data['id'])) {
                return $this->update($data);
            }
            $res = (new VideoModel())->add($data);
            if ($res) {
                return show(1,'','','','', '添加成功');
            } else {
                return show(1,'','','','', '添加失败');
            }
        }
    }

    /**
     * 编辑页面开发
     * @param int $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit($id = 0) {
        $id = $this->MustBePositiveInteger($id);
        //获取服务管理的当前视频分类
        $categorys = ServiceCategoryModel::getSecondCategory($this->currentLanguage['id']);
        $video = VideoModel::get($id);
        return $this->fetch('', [
            'video' => $video,
            'categorys' => $categorys,
            'language_id' => $this->currentLanguage['id'],
        ]);
    }

    /**
     * 彻底删除操作
     * @param Request $request
     * @return array
     */
    public function del(Request $request) {
        $data = $request::instance()->param();
        if (!is_array($data)) {
            return show(0, '', '数据错误');
        }
        $res = VideoModel::destroy($data);
        try {
            if ($res) {
                return show(1, 'success','','','', '删除成功');
            } else {
                return show(0, '','','','', '删除失败');
            }
        } catch (\Exception $e) {
            return show(0, 'error', $e->getMessage());
        }
    }
    /**
     * 排序功能开发
     * 默认 必须数据 id,type,language_id
     **type == 1 时 置底
     * type == 4 时 置顶
     * type == 3 时 上移
     * type == 2 时 下移
     */
    public function listorder() {
        if (request()->isAjax()) {
            $data = input('post.'); //id ,type ,language_id
            self::order(array_filter($data));
        } else {
            return show(0, '置顶失败，未知错误', 'error', 'error', '', '');
        }
    }
}