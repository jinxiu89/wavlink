<?php

namespace app\en_us\controller;

use \app\common\model\Content\About as AboutModel;
use app\common\model\Content\Article as ArticleModel;
use app\common\model\Content\Category as CategoryModel;
use app\common\model\Content\Document as DocumentModel;
use app\common\model\Language as LanguageModel;
use app\common\model\Content\Product as ProductModel;
use app\common\model\System\Setting as SettingModel;
use app\common\service\en_us\BaseService;
use think\App;
use think\Collection;
use think\Controller;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\facade\Config;
use think\facade\Cookie;
use think\facade\Lang;
use think\facade\Request;
use think\facade\Env;
use think\Response;
use think\response\Redirect;

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
    protected $username;
    protected $uid;
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
    /**
     * @var array
     * loadLanguage : 读取语言
     */
    protected $beforeActionList = ['loadLanguage', 'languages', 'getTree', 'getCategory', 'checkMobile', 'checkLang', 'setting', 'Popular', 'articles', 'documents', 'about'];

    /**
     * Base constructor.
     * @param App|null $app
     */
    public function __construct(App $app = null)
    {
        $path = explode('/', Request::path());
        if (!empty($path[0]) && in_array($path[0], Config::get('language.allow_lang'))) {
            Cookie::set('lang_var', $path[0], ['expire' => 3600]);
            Cookie::set('customer_lang', $path[0], ['expire' => 3600]); //lang_var是tp自带的变量 ，跨控制器之后就失效了， 所以这里加一个供会员系统的变量
        }
        //init 之前运行
//        print_r("construct parent之前运行 1"."<br />");
        parent::__construct($app);
        //init 之后运行

    }

    public function initialize()
    {
        parent::initialize();
        //当前模块
        $this->code = Cookie::get('lang_var') ? Cookie::get('lang_var') : get_lang(Request::instance()->header());
        $this->module = Request::module();//模块名
        $url = Request::controller();
        $this->assign('url', $url);
        $this->assign('code', $this->code);
        $user = session('CustomerInfo', '', 'Customer');
        if (isset($user) and !empty($user)) {
            $this->uid = $user['id'];
            $this->username = $user['username'];
            $this->assign('id', $this->uid);
            $this->assign('username', $this->username);
        }
        print_r($this->code);
    }


    /**
     * 关于我们
     */
    public function about()
    {
        $about = (new AboutModel())->getAbouts($this->code);
        $this->assign("about", $about['data']);
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
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     * 检测语言是否被允许访问
     */
    public function checkLang()
    {
        //判断该语言是否是正常状态
        $langStatus = LanguageModel::getIDStatusByCode($this->code);
        if ($langStatus['status'] !== 1) {
            return \redirect('/en_us/index.html');
        }
    }

    /**
     * 检测屏幕终端 跳入移动端还是PC端
     */
    public function checkMobile()
    {
        if (isMobile()) {
            $this->template = Env::get('app_path') . Request::module() . '/view/mobile';
            $this->assign('terminal', '{extend name="mobile/common/base" /}');
        } else {
            $this->template = Env::get('app_path') . Request::module() . '/view/desktop';
            $this->assign('terminal', '{extend name="desktop/common/base" /}');
        }

    }

    /***
     * 根据语言来获取分类
     * 此组件后期弃用
     *
     */
    public function getCategory()
    {
        $category = (new CategoryModel())->getNormalCategory($this->code);//获取一级分类,parent_id = 0
        $this->assign('category', $category);
    }

    /**
     * 新导航产品分类组件全局获取
     *
     */
    public function getTree()
    {
        $data = (new CategoryModel())->getDataByLanguage($this->language_id);
        $tree = [];
        if (!is_array($data) and !empty($data)) {
            $arr = Collection::make($data)->toArray();
            $tree = \app\common\helper\Category::toLayer($arr, $name = 'child', $parent_id = 0);
        }
        $this->assign('tree', $tree);
    }

    /***
     * 获取语言列表
     */
    public function languages()
    {
        $language = LanguageModel::all(['status' => 1]);
        $this->assign("language", $language);
    }

    /**
     * @return Redirect
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
        $this->language_id = LanguageModel::getLanguageCodeOrID($this->code);
        //加载当前模块语言包
        Lang::load(Env::get('app_path') . $this->module . '/lang/' . $this->code . '.php');
        $this->assign('code', $this->code);
        return \redirect('/' . $this->code . '/index.html', [], 200);
    }

    /**
     * notFound : 通过response 创建 一个状态码为404的页面
     * @return Response
     */
    public function notFound()
    {
        //todo:: 这里修改这个控制器的状态码为404
        return Response::create($template = '', 'view', 404);
    }

    /**
     * @return Response
     *
     */
    public function serverError()
    {
        return Response::create('', 'view', 500);
    }


    /**
     * @return mixed
     */
    public function IE()
    {
        return $this->fetch($this->template . '/common/ie.html');
    }

    /**
     *
     */
    public function popularProduct()
    {
        $category_id = input('get.category_id');
        if (empty($category_id) and is_numeric($category_id)) return json(['status' => 0, 'message' => 'category_id必须为数字', 'data' => []]);
        $data = (new BaseService())->popularProduct($this->language_id, $category_id);
        return json(['status' => 1, 'message' => 'ok', 'data' => $data]);
    }

}