<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/4/24
 * Time: 14:08
 */
namespace app\en_us\controller;

use app\common\model\ServiceCategory as ServiceCategoryModel;
use app\common\model\Document as DocumentModel;
use think\Request;

class Document extends Base
{

    protected $beforeActionList = [
        'cate' => ['only', 'index,details'],
//        'docCount' => ['only','index,details']
    ];
//    public function docCount(){
//        //得到总的数量，对搜索框进行隐藏
//        $document = (new DocumentModel())->getDocument('',$this->code);
//        $docCount= $document['count'];
//        $this->assign('docCount',$docCount);
//    }
    public function index($url_title=''){

        if (empty($url_title) || !isset($url_title)){
            //获取Document的一级分类
            $parent = ServiceCategoryModel::getTopCategory($this->code,'Document');
            //获取所有的文档
            $document = (new DocumentModel())->getDocument('',$this->code);
        }else{
            //获取当前选择的分类信息
            $parent = ServiceCategoryModel::getCategoryIdByName($this->code,$url_title);
            //得到当前分类的文档
            $document = (new DocumentModel())->getDocument($parent['id'],$this->code);
        }

        if (empty($parent)){
            abort(404);
        }else{
            return $this->fetch($this->template.'/document/index.html', [
                'parent' => $parent,
                'document' => $document['data'],
            ]);
        }

    }


    public function details($document=''){
        if(!isset($document) || empty($document)){
            abort(404);
        }
        $result=DocumentModel::getDetailsByUrlTitle($document,$this->code);
        //该文档的分类
        $docCate = ServiceCategoryModel::get(['id' => $result['category_id']]);
        if(!empty($result)){
            return $this->fetch($this->template.'/document/details.html',[
                'result'=>$result,
                'docCate' => $docCate
            ]);
        }else{
            abort(404);
        }
    }
}