<?php
/**
 * Created by PhpStorm.
 * User: wavlink
 * Date: 2017/11/25
 * Time: 10:15
 */

namespace app\en_us\controller;

use app\common\model\Drivers as DriversModel;
use app\common\model\Faq as FaqModel;
use app\common\model\Manual as ManualModel;
use app\common\model\Product as ProductModel;
use app\lib\exception\BannerMissException;
use app\common\model\ServiceCategory as ServiceCategoryModel;
use app\common\helper\Search as elSearch;
use think\App;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\facade\Request;
use think\paginator\driver\Bootstrap;
use think\Paginator;

class Search extends Base
{
    protected $elSearch;

    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->elSearch = new elSearch();
    }

    /**\
     * 搜索，先展示产品
     */
    public function index()
    {
        $data = ProductModel::allData($this->language_id);

    }

    /**
     * @return mixed
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public function results()
    {
        if (Request::isGet()) {
            $search = input('key', '');
            $keywords = array_filter(explode(' ', $search));
            $type = input('type', 'product');
            $pages = input('page', 1);
            $size = 12;//后面加配置里去
            $this->elSearch->paginate($pages, $size);
            //产品
            $product_field = ['keywords', 'name', 'url_title', 'model', 'seo_title', 'description', 'features'];
            $this->elSearch->keywords($keywords, $product_field);
            $this->elSearch->Index('products_' . $this->language_id);
            $products = $this->elSearch->Client()->search($this->elSearch->getParams());
            //驱动
            $driver_field = ['name', 'url_title', 'keywords', 'descrip'];
            $this->elSearch->keywords($keywords, $driver_field);
            $this->elSearch->Index('drivers_' . $this->language_id);
            $drivers = $this->elSearch->Client()->search($this->elSearch->getParams());
            $page_options = ['var_page' => 'page', 'path' => '/' . $this->code . '/search', 'query' => ['key' => $search, 'type' => $type]];

            $product_total = $products['hits']['total']['value'];
            $driver_total = $drivers['hits']['total']['value'];

            if ($type == 'product') {
                $product_page = Bootstrap::make($products['hits']['hits'], $size, $pages, $product_total, false, $page_options);
                $this->assign('products', $products['hits']['hits']);
                $this->assign('product_page', $product_page);
            }
            if ($type == 'driver') {
                $ids = [];
                foreach ($drivers['hits']['hits'] as $driver) {
                    $ids[] = $driver['_id'];
                }
                $items = Db::table('drivers')->where('id', 'in', $ids)->select();
                $data = [];
                foreach ($items as $item) {
                    $item['modelsGroup'] = explode(',', $item['models']);
                    $data[] = $item;
                }
                $driver_page = Bootstrap::make($drivers['hits']['hits'], $size, $pages, $driver_total, false, $page_options);
                $this->assign('drivers', $data);
                $this->assign('driver_page', $driver_page);
            }
            $this->assign('type', $type);
            $this->assign('search', $search);
            $this->assign('product_total', $product_total);
            $this->assign('driver_total', $driver_total);
            return $this->fetch($this->template . '/search/results.html');
        }
    }

    public function wildcard()
    {

    }

    //驱动搜索
    public function drivers()
    {
        $key = input('get.key', '', 'trim');
        if ($key == '' || empty($key)) {
            return $this->fetch($this->template . '/search/drivers.html', [
                'count' => '0',
                'result' => '',
                'name' => ''
            ]);
        } else {
            $res = (new DriversModel)->getSelectDrivers($key, $this->code);
            $result = ModelsArr($res['data'], 'models', 'modelsGroup');
            $page = $result->render();
            return $this->fetch($this->template . '/search/drivers.html', [
                'result' => $result,
                'count' => $res['count'],
                'name' => $key,
                'page' => $page,
            ]);
        }
    }

    //faq搜索
    public function faq()
    {
        $key = input('get.key', '', 'trim');
        //获取一级faq分类
        $parent = ServiceCategoryModel::getTopCategory($this->code, 'faq');
        $cate = ServiceCategoryModel::getSecondCategory($this->code, 'faq');
        $res = (new FaqModel())->getSelectFaq($this->code, $key);
        if ($key == '' || empty($key) || !$res) {
            $this->assign('faq', '');
            $this->assign('count', 0);
        } else {
            $this->assign('faq', $res['data']);
            $this->assign('count', $res['count']);
        }
        $this->assign('parent', $parent);
        $this->assign('cate', $cate);
        $this->assign('name', $key);
        return $this->fetch($this->template . '/search/faq.html');
    }

    public function manual()
    {
        $key = input('get.key', '', 'trim');
        //获取一级faq分类
        $parent = ServiceCategoryModel::getTopCategory($this->code, 'Manual');
        $cate = ServiceCategoryModel::getSecondCategory($this->code, 'Manual');
        $res = (new ManualModel())->getSelectManual($this->code, $key);
        if ($key == '' || empty($key) || !$res) {
            $this->assign('manual', '');
            $this->assign('count', 0);
        } else {
            $this->assign('manual', $res['data']);
            $this->assign('count', $res['count']);
        }
        $this->assign('parent', $parent);
        $this->assign('cate', $cate);
        $this->assign('name', $key);
        return $this->fetch($this->template . '/search/manual.html');
    }
}