<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:46
 */

namespace app\wavlink\controller;

use app\common\model\Images as ImagesModel;
use app\common\model\Featured as FeaturedModel;
use app\wavlink\validate\Images as ImagesValidate;
use think\Exception;
use think\Facade\Request;

/***
 * Class Images
 * @package app\wavlink\controller
 * 首页推荐位图片产品管理
 */
Class Images extends BaseAdmin
{
    /***
     * @return mixed|\think\response\View
     * @throws \think\exception\DbException
     * 20190912
     * 移出url多余的参数
     *
     */
    public function index()
    {
        $featured = FeaturedModel::all(['status' => 1]);
        $featured_id = input('get.featured_id');
        if (!empty($featured_id)) {
            $res = (new ImagesModel())->getImagesByFeatured($this->currentLanguage['id'], $featured_id);
            return view('', [
                'image' => $res['data'],
                'counts' => $res['count'],
                'featured' => $featured,
                'featured_id' => $_GET['featured_id'],
                'language_id' => $this->currentLanguage['id']
            ]);
        }
        $result = (new ImagesModel())->getImages(1, $this->currentLanguage['id']);
        return $this->fetch('', [
            'image' => $result['data'],
            'counts' => $result['count'],
            'featured' => $featured,
            'language_id' => $this->currentLanguage['id'],
            'featured_id' => ''
        ]);
    }

    //回收站图片列表,status=-1
    public function images_recycle()
    {
        $result = ImagesModel::getDataByStatus(-1, $this->currentLanguage['id']);
        return $this->fetch('', [
            'image' => $result['data'],
            'counts' => $result['count'],
        ]);
    }

    public function add()
    {
        //获取推荐位
        $featured = FeaturedModel::all(['status' => 1]);
        return $this->fetch('', [
            'featured' => $featured,
            'language_id' => $this->currentLanguage['id']
        ]);
    }

    /**
     * @return array|void
     *
     */
    public function save()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            $validate = new ImagesValidate();
            if (isset($data['id']) and !empty($data['id'])) {
                if ($validate->scene('edit')->check($data)) {
                    try {
                        return $this->update($data);
                    } catch (\Exception $exception) {
                        return show(0, '', '', '', '', $exception->getMessage());
                    }
                }
            } else {
                if ($validate->scene('add')->check($data)) {
                    try {
                        $res = (new ImagesModel())->add($data);
                        if ($res) {
                            return show(1, '', '', '', '', '添加成功');
                        } else {
                            return show(0, '', '', '', '', '添加失败,未知原因');
                        }
                    } catch (\Exception $exception) {
                        return show(0, '', '', '', '', $exception->getMessage());
                    }
                }
            }
            return show(0, '', '', '', '', $validate->getError());
        }
    }

    public function edit($id = 0)
    {
        //获取正常的推荐位分类
        $featured = FeaturedModel::all(['status' => 1]);
        $images = ImagesModel::get($id);
        return $this->fetch('', [
            'images' => $images,
            'featured' => $featured,
            'language_id' => $this->currentLanguage['id']
        ]);
    }

    //批量修改
    public function allChange(Request $request)
    {
        try {
            $ids = $request::instance()->post();
            foreach ($ids as $k => $v) {
                if (ImagesModel::get($k)) {
                    //批量更新数据。
//                    (new CategoryModel())->where('id', $k)->update(['status' => -1]);
                    //批量更新排序
                    (new ImagesModel())->where('id', $k)->update(['listorder' => $k + 100]);
                } else {
                    return show(0, '', '回收失败');
                }
            }
            return show(1, '', '批量回收成功');
        } catch (Exception $e) {
            return show(0, $e->getMessage(), '', '', '');
        }
    }

    /**
     * @return array|void
     */
    public function byStatus()
    {
        $data = input('get.');
        $check['status'] = number_format($data['status']);
        $validate = new ImagesValidate();
        if ($validate->scene('changeStatus')->check($data)) {
            try {
                if ((new ImagesModel())->allowField(true)->save($data, ['id' => $data['id']])) {
                    return show(1, "success", '', '', '', '操作成功');
                }
                return show(0, "success", '', '', '', '操作失败！未知原因');
            } catch (\Exception $exception) {
                return show(0, "failed", '', '', '', $exception->getMessage());
            }
        }
        return show(0, "failed", '', '', '', $validate->getError());
    }

    /**
     *
     */
    public function del(){
        if(Request::isPost()){
            $id=input('get.id');
            $validate=new ImagesValidate();
            if(!$validate->scene('del')->check(['id'=>$id])){
                return show(0,'failed','','','',$validate->getError());
            }
            try{
                if(ImagesModel::destroy($id)){
                    return show(1, "success", '', '', '', '操作成功');
                }
                return show(0, "success", '', '', '', '操作失败！未知原因');
            }catch (\Exception $exception){
                return show(0,'failed','','','',$exception->getMessage());
            }
        }
    }
    //排序操作
    public function listorder()
    {
        if (request()->isAjax()) {
            $data = input('post.'); //id ,type ,language_id
            $validate= new ImagesValidate();
            if(!$validate->scene('listorder')->check($data)){
                return show(0, '排序失败', 'error', 'error', '', $validate->getError());
            }
            try {
                if ((new ImagesModel())->allowField(true)->save($data, ['id' => $data['id']])) {
                    return show(1, '排序成功', 'error', 'error', '', "排序成功");
                }
                return show(0, '排序失败，未知错误', 'error', 'error', '', "排序失败，未知错误");
            } catch (\Exception $exception) {
                return show(0, '排序失败，数据库错误', 'error', 'error', '', $exception->getMessage());
            }
        } else {
            return show(0, '置顶失败，未知错误', 'error', 'error', '', '');
        }
    }
}