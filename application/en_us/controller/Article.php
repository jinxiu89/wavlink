<?php
/**
 * Created by PhpStorm.
 * User: wavlink
 * Date: 2017/11/25
 * Time: 14:24
 * * 事件管理
 */
namespace app\en_us\controller;

use app\common\model\Article as ArticleModel;
use app\common\model\ServiceCategory as ServiceCategoryModel;
class Article extends Base{
    protected $beforeActionList = [
        'cate' => ['only', 'index,details']
    ];

    public function index($url_title=''){

        if (empty($url_title) || !isset($url_title)){
            //获取Article的一级分类
            $parent = ServiceCategoryModel::getTopCategory($this->code,'Article');
            //获取所有的文章
            $article = (new ArticleModel())->getArticle('',$this->code);
        }else{
            //获取当前选择的分类信息
            $parent = ServiceCategoryModel::getCategoryIdByName($this->code,$url_title);
            //得到当前分类的事件文章
            $article = (new ArticleModel())->getArticle($parent['id'],$this->code);
        }
        if (empty($parent)){
            abort(404);
        }else{
            return $this->fetch($this->template.'/article/index.html', [
                'parent' => $parent,
                'article' => $article['data']
            ]);
        }
    }


    public function details($article=''){
        if(!isset($article) || empty($article)){
            abort(404);
        }
        $result=ArticleModel::getDetailsByUrlTitle($article,$this->code);
        //该文章的分类
        $articleCate = ServiceCategoryModel::get(['id' => $result['category_id']]);
        if(!empty($result)){
            return $this->fetch($this->template.'/article/details.html',[
                'result'=>$result,
                'articleCate' => $articleCate
            ]);
        }else{
            abort(404);
        }
    }
}