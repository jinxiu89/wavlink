<?php

namespace app\en_us\controller;

use app\common\model\About as AboutModel;
use app\common\model\Category as CategoryModel;
use app\common\model\Language as LanguageModel;
use app\common\model\Product as ProductModel;
use app\common\model\Article as ArticleModel;
use app\common\model\ServiceCategory as ServiceCategoryModel;
use app\common\model\Setting as SettingModel;
use think\Controller;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Lang;
use think\Request;
use think\Cookie;
use app\common\model\Document as DocumentModel;

/***
 * Class Base
 * @package app\en_us\controller
 * 前台基类，用于初始化以及初始设定，如语言问题SEO 和公共模块内容等
 *
 */
class Base extends Controller
{
    /**
     * @var $module :当前的模块名
     */
    protected $module;
    /**
     * @var $code : 当前的语言code 通过浏览器获得或者用户自定义选择获得
     */
    protected $code;
    /**
     * @var $language_id
     */
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
    protected $beforeActionList = ['loadLanguage', 'languages', 'getCategory', 'checkMobile', 'checkLang', 'setting', 'Popular', 'articles', 'documents', 'about'];


    public function _initialize()
    {
        //当前模块
        $this->module = Request::instance()->module();
        try {
            $this->language_id = LanguageModel::getLanguageCodeOrID($this->code);
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }//$code 转成 language_id
        //这两个玩意暂时还没找到用途先放在这里
        $url = Request::instance()->controller();
        $this->assign('url', $url);
    }

    /***
     * @param $code
     * @return \think\response\Redirect
     * 手动设置语言
     *
     */
    public function setLanguage($code)
    {
        Cookie::set('lang_var', $code);
        $this->code = $code;
        return redirect('/' . $code . '/index.html', [], 200);
    }

    /**
     * 关于我们
     */
    public function about()
    {
        $about = (new AboutModel())->getAbouts($this->code);
        $this->assign("about", $about);
    }

    /**
     * 文档
     */
    public function documents()
    {
        $documentList = (new DocumentModel())->getDocumentList($this->code, 5);
        $this->assign('documentList', $documentList);

    }

    /**
     * 页脚的文章
     *
     */
    public function articles()
    {
        $articleList = (new ArticleModel())->getArticleList($this->code, 5);
        $this->assign('articleList', $articleList);
    }

    /**
     * 推荐 结果为空时显示到列表页
     */
    public function Popular()
    {
        //当一些搜索结果为空的时候，就推荐这些产品，目前是找出排序最高的三个产品
        $imagesRecommend = ProductModel::getListProduct($this->code, 3);
        $this->assign('imagesRecommend', $imagesRecommend);
    }

    /**
     * 载入主SEO信息
     * //todo:: SEO还没搞好，后面来收拾
     */
    public function setting()
    {
        try {
            $seo = (new SettingModel())->getSeo($this->code);
            $this->assign("seo", $seo);
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
    }

    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 检测语言是否被允许访问
     */
    public function checkLang()
    {
        //判断该语言是否是正常状态
        $langStatus = LanguageModel::getIDStatusByCode($this->code);
        if ($langStatus['status'] !== 1) {
            abort(404);
        }
    }

    /**
     * 检测屏幕终端 跳入移动端还是PC端
     */
    public function checkMobile()
    {
        if (isMobile()) {
            $this->template = APP_PATH . request()->module() . '/view/mobile';
        } else {
            $this->template = APP_PATH . request()->module() . '/view/desktop';
        }
    }

    /***
     * 根据语言来获取分类
     *
     *
     */
    public function getCategory()
    {
        $category = (new CategoryModel())->getNormalCategory($this->code);//获取一级分类,parent_id = 0
        $this->assign('category', $category);
    }

    /***
     * @throws \think\exception\DbException
     * 获取语言列表
     */
    public function languages()
    {
        $language = LanguageModel::all(['status' => 1]);
        $this->assign("language", $language);
    }

    /**
     * @return \think\response\Redirect
     * 自动跳语言并加载指定的语言文件
     *
     */
    public function autoload()
    {
        $code = Cookie::get('lang_var') ? Cookie::get('lang_var') : get_lang(Request::instance()->header()); //这个code需要把‘-’换成‘_’;
        return redirect('/' . $code . '/index.html', [], 200);
    }

    /***
     * 读取翻译文件
     * url中间手动修改语言是无效的必须要通过跳转才有用
     */
    public function loadLanguage()
    {
        $this->code = Cookie::get('lang_var') ? Cookie::get('lang_var') : get_lang(Request::instance()->header()); //code 不能重request里拿了，需要从cookie里拿
        //加载当前模块语言包
        Lang::load(APP_PATH . $this->module . '/lang/' . $this->code . '.php');
        $this->assign('code', $this->code);
    }


    /**
     * @return mixed
     */
    public function IE()
    {
        return $this->fetch($this->template . '/base/ie.html');
    }

    /***
     * $this->code 为 当前的模块名，即在上面_initialize(初始化中)赋予的
     *
     */
    protected function cate()
    {
        //获取服务分类下对应的二级分类
        try {
            $cate = ServiceCategoryModel::getSecondCategory($this->code);
            $this->assign('cate', $cate);
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
    }
}