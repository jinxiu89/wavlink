<?php
namespace app\zh_cn\controller;
use app\common\model\About as AboutModel;
use app\common\model\Category as CategoryModel;
use app\common\model\Document as DocumentModel;
use app\common\model\Language as LanguageModel;
use app\common\model\Product as ProductModel;
use app\common\model\Article as ArticleModel;
use app\common\model\ServiceCategory as ServiceCategoryModel;
use app\common\model\Setting as SettingModel;
use think\Controller;
use think\Lang;
use think\Request;

class Base extends Controller
{
    protected $code;//受保护的成员变量
    protected $obj;
    protected $template;
    public function _initialize()
    {
        //      获取当前模块名，由于我们的多语言网站是一个语种一个模块，所以需要获取；
        $this->code = request()->module();
        //判断该语言是否是正常状态
        $langStatus = LanguageModel::getIDStatusByCode($this->code);
        if ($langStatus['status'] !== 1){
            abort(404);
        }
        if(isMobile()){
            $this->template=APP_PATH.request()->module().'/view/mobile';
        }else{
            $this->template=APP_PATH.request()->module().'/view/pc';
        }
        $this->obj = model("ServiceCategory");
        $language =LanguageModel::all(array('status'=>1));;
        $category = (new CategoryModel())->getNormalCategory($this->code);//获取一级分类,parent_id = 0
        $imagesRecommend = ProductModel::getListProduct($this->code,3);
        $seo = (new SettingModel())->getSeo($this->code);
        $about = (new AboutModel())->getAbouts($this->code);
        $articleList = (new ArticleModel())->getArticleList($this->code,5);
        $documentList = (new DocumentModel())->getDocumentList($this->code,5);
        $url=Request::instance()->controller();
        $this->assign("language", $language);
        $this->assign("category", $category);
        $this->assign("seo", $seo);
        $this->assign("about", $about);
        $this->assign('code', $this->code);
        $this->assign('articleList', $articleList);
        $this->assign('documentList',$documentList);
        $this->assign('url',$url); //对于搜索框的隐藏和显示用到的变量，
        $this->assign('imagesRecommend',$imagesRecommend);
        //加载当前模块语言包
        Lang::load(APP_PATH .'zh_cn/lang/lang.php');
    }
    //IE9以下浏览器打开跳转页面
    public function IE(){
        return $this->fetch($this->template.'/base/ie.html');
    }
    // 前置方法 video,faq,article共用的
    public function cate(){
        //获取服务分类的二级分类
        $cate = ServiceCategoryModel::getSecondCategory($this->code);
        $this->assign('cate',$cate);
    }
}