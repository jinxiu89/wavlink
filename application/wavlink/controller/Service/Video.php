<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:37
 * 下载中心
 */
namespace app\wavlink\controller\Service;
use app\common\model\ServiceCategory as ServiceCategoryModel;
use app\common\model\Video as VideoModel;
use app\wavlink\controller\BaseAdmin;
use app\wavlink\validate\Video as VideoValidate;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Facade\Request;

/***
 * Class Video
 * @package app\wavlink\controller
 */
Class Video extends BaseAdmin
{
    protected $model;
    protected $validate;

    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->model=new VideoModel();
        $this->validate= new VideoValidate();
    }

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
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public function add() {
        //获取服务管理的当前视频分类
        $categorys = ServiceCategoryModel::getTree($this->currentLanguage['id'],'Videos');
        return $this->fetch('', [
            'categorys' => $categorys,
            'language_id' => $this->currentLanguage['id'],
            'title'=>substr(md5(time() . uniqid(microtime(true), true)), 3, 10)
        ]);
    }

    /**
     * 保存操作
     * @param Request $request
     * @return array
     */
    public function save(Request $request) {
        if (request()->isAjax()) {
            $data = $request::instance()->post();
            if(isset($data['id']) || !empty($data['id'])){
                if($this->validate->scene('edit')->check($data)){
                    try{
                        return $this->update($data);
                    }catch (\Exception $exception){
                        return show(0,'','','','', $exception->getMessage());
                    }
                }
            }else{
                if($this->validate->scene('add')->check($data)){
                    try{
                        $res = (new VideoModel())->add($data);
                        if ($res) {
                            return show(1,'','','','', '添加成功');
                        } else {
                            return show(0,'','','','', '添加失败');
                        }
                    }catch (\Exception $exception){
                        return show(0,'','','','', $exception->getMessage());
                    }
                }
            }
            return show(0,'','','','', $this->validate->getError());
        }
    }

    /**
     * 编辑页面开发
     * @param int $id
     * @return mixed
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public function edit($id = 0) {

        $categorys = ServiceCategoryModel::getTree($this->currentLanguage['id'],'Videos');
//        $id = $this->MustBePositiveInteger($id);
        //获取服务管理的当前视频分类
        $video = VideoModel::get($id);
        return $this->fetch('', [
            'categorys'=>$categorys,
            'video' => $video,
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