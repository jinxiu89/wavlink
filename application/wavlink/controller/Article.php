<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:37
 */
namespace app\wavlink\controller;

use app\wavlink\validate\UrlTitleMustBeOnly;
use think\Request;
use app\common\model\Article as ArticleModel;
use app\wavlink\validate\Article as ArticleValidate;
use app\common\model\ServiceCategory as ServiceCategoryModel;
Class Article extends BaseAdmin
{

    //正常文章列表，status=1
    public function index() {
        $language_id = input('get.language_id', '', 'intval');
        $result = (new ArticleModel())->getDataByStatus(1,$language_id);
        return $this->fetch('', [
            'article' => $result['data'],
            'counts' => $result['count'],
            'language_id' => $language_id

        ]);
    }

    //回收站文章列表,status=-1
    public function article_recycle() {
        $result = (new ArticleModel())->getDataByStatus(-1);
        return $this->fetch('', [
            'counts' => $result['count'],
            'article' => $result['data'],
        ]);
    }

    public function add() {
        $language_id = input('get.language_id','','intval');
        $categorys = ServiceCategoryModel::getSecondCategory($language_id);
        return $this->fetch('', [
            'categorys' => $categorys,
            'language_id'=> $language_id
        ]);
    }

    //新增保存文章
    public function save() {
        if (request()->isAjax()){
            (new ArticleValidate())->goCheck();
            (new UrlTitleMustBeOnly())->goCheck();
            $data = input('post.');
            if (!empty($data['id'])){
                return $this->update($data);
            }
            $res = (new ArticleModel())->add($data);
            if ($res) {
                return show(1,'','','','', '添加成功');
            } else {
                return show(1,'','','','', '添加失败');
            }
        }
    }

    //编辑文章
    public function edit($id= 0,$language_id) {
        $id = $this->MustBePositiveInteger($id);
        $language_id = $this->MustBePositiveInteger($language_id);
        $categorys = ServiceCategoryModel::getSecondCategory($language_id);
        $article = ArticleModel::get($id);
        return $this->fetch('', [
            'article' => $article,
            'categorys' => $categorys,
            'language_id' => $language_id
        ]);
    }

    /**
     *
     * 批量回收文章
     * @param Request $request
     * @return array
     */
    public function allRecycle(Request $request) {
        $ids = $request::instance()->post();
        foreach ($ids as $k => $v) {
            if (ArticleModel::get($k)) {
                model('Article')->where('id', $k)->update(['status' => -1]);
//                model('Article')->where('id', $k)->update(['listorder' => $k + 100]);
            } else {
                return show(0, '', '回收失败');
            }
        }
        return show(1, '', '批量回收成功');
    }

    /**
     * 排序功能开发
     */
    public function listorder(){
        if(request()->isAjax()){
            $data = input('post.'); //id ,type ,language_id
            self::order(array_filter($data));
        }else {
            return show(0, '置顶失败，未知错误', 'error', 'error', '', '');
        }
    }

//    //回收站内彻底删除文章
//    public function del(Request $request){
//        //获取传入的id
//        $id = $request->param('id');
//        if(empty($id)){
//            return show(0,'','数据不存在');
//        }
//        $res = \app\common\model\Article::destroy($id);
//        if ($res){
//            return show(1,'','删除成功');
//        }else{
//            return show(0,'','删除失败');
//        }
//    }
//    //回收站彻底批量删除文章
//    public function delAll(Request $request){
//        $ids = $request::instance()->post();
//        if (!is_array($ids)){
//            return show(0,'','数据错误');
//        }
//        try{
//            foreach ($ids as $k=>$v){
//                if (\app\common\model\Article::get($k)){
//                     \app\common\model\Article::destroy($k);
//                    }else{
//                        return show(0,'','批量删除失败');
//                    }
//                }
//            return show(1,'','批量删除成功');
//        }catch (\Exception $e){
//            return show(0,'','发生了异常');
//        }
//    }
}