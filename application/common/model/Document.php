<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */
namespace app\common\model;
use app\common\model\Language as LanguageModel;
Class Document extends BaseModel
{
    protected $table="document";
    public function getDocument($category_id='',$language_id){
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
                'category_id' => $category_id
            ];
        }
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        return Search('Document',$data,$order);
    }
    public function getSelectDoc($name){
        //多条件模糊查询
        $model = 'Document';
        $map['status']='1';
        $map['name|title|url_title|seo_title|keywords']=array('like','%'.$name.'%');
        $map['language_id']='2';
        $order=[
            'id'=>'desc',
        ];
      return  Search($model,$map,$order);
    }
    //前台获取数据开始，调用5篇排序最高的文档，
    public function getDocumentList($code,$limit){
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $map=[
            'status'=>1,
            'language_id'=>$language_id,
        ];
        $order = [
            'listorder' => 'desc',
            'id'      => 'desc'
        ];
        return $this->where($map)
            ->order($order)
            ->limit($limit)
            ->field('title,url_title')
            ->order('id desc')
            ->select();
    }

}