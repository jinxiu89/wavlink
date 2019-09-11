<?php
namespace app\en_us\controller;

use app\common\model\About as AboutModel;
use app\common\model\Category as CategoryModel;
use app\common\model\Language as LanguageModel;
use app\common\model\Product as ProductModel;
use app\common\model\Article as ArticleModel;
use app\common\model\ServiceCategory as ServiceCategoryModel;
use app\common\model\Setting as SettingModel;
use app\common\model\Language as LanugaeModel;
use think\Controller;
use think\Lang;
use think\Request;
use app\common\model\Document as DocumentModel;
class Base extends Controller
{
    protected $code;//受保护的成员变量
    protected $language_id; //全局的语言ID
    protected $language; //语言
    protected $category; // 产品分类列表
    protected $imagesNew; // 最新产品
    protected $imageBest; //最热产品
    protected $imagesRecommend;//推荐产品列表
    protected $seo; //网站信息SEO
    protected $about; // 关于我们
    protected $articleList; // 最新事件列表
    protected $productList; // 最热产品列表
    protected $template;
    public function _initialize()
    {
//        $this->obj = model("ServiceCategory");
//      获取当前模块名，由于我们的多语言网站是一个语种一个模块，所以需要获取；
        $this->code = request()->module();
        $this->language_id=LanugaeModel::getLanguageCodeOrID($this->code);//$code 转成 language_id
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
        $language = LanguageModel::all(array('status'=>1));
        $category = (new CategoryModel())->getNormalCategory($this->code);//获取一级分类,parent_id = 0
//        $category= (new CategoryModel())->getChildsCategory($this->code);//导航获取分类
        //当一些搜索结果为空的时候，就推荐这些产品，目前是找出排序最高的三个产品
        $imagesRecommend = ProductModel::getListProduct($this->code,3);
        $seo = (new SettingModel())->getSeo($this->code);
        $about = (new AboutModel())->getAbouts($this->code);
        $articleList = (new ArticleModel())->getArticleList($this->code,5);
        $documentList = (new DocumentModel())->getDocumentList($this->code,5);
        $url=Request::instance()->controller();
        $this->assign("language", $language);
        $this->assign('category',$category);
        $this->assign("seo", $seo);
        $this->assign("about", $about);
        $this->assign('code', $this->code);
        $this->assign('articleList', $articleList);
        $this->assign('documentList',$documentList);
        $this->assign('url',$url);
        $this->assign('imagesRecommend',$imagesRecommend);
        //加载当前模块语言包
        Lang::load(APP_PATH .'en_us/lang/lang.php');
    }
    //404页面
    public function NotFound(){
        return view($this->template.'/base/404.html',$code=404);
    }

    //IE9以下浏览器打开跳转页面
    public function IE(){
        return $this->fetch($this->template.'/base/ie.html');
    }
    /***
     * $this->code 为 当前的模块名，即在上面_initialize(初始化中)赋予的
     *
     */
    protected function cate() {
        //获取服务分类下对应的二级分类
        $cate = ServiceCategoryModel::getSecondCategory($this->code);
        $this->assign('cate', $cate);
    }
}