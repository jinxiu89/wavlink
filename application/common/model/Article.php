<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */
namespace app\common\model;
use app\common\model\Language as LanguageModel;
Class Article extends BaseModel
{
    protected $table="article";//使用article表
    public function getArticle($id='',$language_id){
        $language_id = LanguageModel::getLanguageCodeOrID($language_id);
        if (empty($id)){
            $data = [
                'status'=>1,
                'language_id'=>$language_id
            ];
        }else{
            $data = [
                'status' => 1,
                'language_id' => $language_id,
                'category_id' => $id
            ];
        }

        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        return Search('Article',$data,$order);
    }

    //前台获取数据开始，调用5篇排序最高的文章，
    public function getArticleList($code,$limit){
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $map=[
            'status'=>1,
            'language_id'=>$language_id,
        ];
        $order = [
          'listorder' => 'desc',
            'id'      => 'desc'
        ];
        return $this->where($map)->order($order)->limit($limit)->field('title,url_title')->order('id desc')->select();
    }


}