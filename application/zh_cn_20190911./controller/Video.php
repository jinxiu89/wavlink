<?php
namespace app\zh_cn\controller;
use app\common\model\Video as VideoModel;
use app\common\model\ServiceCategory as ServiceCategoryModel;
class  Video extends Base
{
    public function index($category = "")
    {
        $videos = new VideoModel();
        //获取二级分类
        $child = ServiceCategoryModel::getSecondCategory($this->code);
        if ($category == ""){
            $parent = ServiceCategoryModel::getTopCategory($this->code,'Video');
            if (empty($parent) || !isset($parent)){
                abort(404);
            }
            //获取当前语言下的所有视频列表
            $video =$videos->getVideoByLanguage($this->code);
            $this->assign('parent',$parent);
            $this->assign('video',$video['data']);
        }else{
            //获取选择的分类信息
            $parent = ServiceCategoryModel::getCategoryIdByName($this->code,$category);
            //获取该分类下的视频列表
            $video = $videos->getVideosByCategoryId($this->code,$parent['id']);
            $this->assign('parent',$parent);
            $this->assign('video',$video['data']);
        }
        return $this->fetch($this->template.'/video/index.html',[
            'child'=>$child
        ]);

    }

    //视频详情页
    public function detail($video = ""){
        if (empty($video) || !isset($video)){
            abort(404);
        }
        $result = VideoModel::getDetailsByUrlTitle($video,$this->code);
        if(!empty($result)){
            return $this->fetch($this->template.'/video/detail.html',[
                'result'=>$result
            ]);
        }else{
            abort(404);
        }
    }
}