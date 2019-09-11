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
use think\Request;
Class Images extends BaseAdmin
{
    //幻灯片图片
    public function index() {
        $featured = FeaturedModel::all(['status' => 1]);
        $language_id = $this->MustBePositiveInteger(input('get.language_id'));
        if (!empty(input('get.featured_id'))) {
            $res = (new ImagesModel())->getImagesByFeatured($language_id,input('get.featured_id'));
            return view('', [
                'image' => $res['data'],
                'counts' => $res['count'],
                'featured' => $featured,
                'featured_id' => $_GET['featured_id'],
                'language_id' => $language_id
            ]);
        }

        $result = (new ImagesModel())->getImages(1,$language_id);
        return $this->fetch('', [
            'image' => $result['data'],
            'counts' => $result['count'],
            'featured' => $featured,
            'language_id' => $language_id,
            'featured_id' => ''
        ]);
    }

    //回收站图片列表,status=-1
    public function images_recycle() {
        $result = ImagesModel::getDataByStatus(-1);
        return $this->fetch('', [
            'image' => $result['data'],
            'counts' => $result['count'],
        ]);
    }

    public function add() {
        //获取语言
        $language_id = input('get.language_id', '', 'intval');
        //获取推荐位
        $featured = FeaturedModel::all(['status' => 1]);
        return $this->fetch('', [
            'featured' => $featured,
            'language_id' => $language_id
        ]);
    }

    public function save() {
        if (request()->isAjax()){
            (new ImagesValidate())->goCheck();
            $data=input('post.');
            if (!empty($data['id'])){
                return $this->update($data);
            }
            $res = (new ImagesModel())->add($data);
            if ($res) {
                return show(1,'','','','', '添加成功');
            } else {
                return show(1,'','','','', '添加失败');
            }
        }
    }

    public function edit($id = 0) {
        //获取正常的推荐位分类
        $featured = FeaturedModel::all(['status' => 1]);
        //获取语言
        $language_id = $this->MustBePositiveInteger(input('get.language_id'));
        $images = ImagesModel::get($id);
        return $this->fetch('', [
            'images' => $images,
            'featured' => $featured,
            'language_id' => $language_id
        ]);
    }

    //批量修改
    public function allChange(Request $request) {
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
        } catch (\Exception $e) {
            return show(0, $e->getMessage(), '', '', '');
        }
    }

    //排序操作
    public function listorder() {
        if (request()->isAjax()) {
            $data = input('post.'); //id ,type ,language_id
            $map = [
                'featured_id'=>$data['map'],
            ];
            unset($data['map']);
            self::order($data, $map);
        } else {
            return show(0, '置顶失败，未知错误', 'error', 'error', '', '');
        }
    }
}