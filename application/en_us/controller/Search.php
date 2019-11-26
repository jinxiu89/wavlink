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
use think\facade\Request;
use think\paginator\driver\Bootstrap;
use think\Paginator;

class Search extends Base
{

    /**\
     * 搜索，先展示产品
     */
    public function index()
    {
        $data = ProductModel::allData($this->language_id);
        print_r($data);

    }


    /**
     * @return mixed
     * 利用elasticsearch来构建全文搜索，重要知识点
     * 1、$pages : 分页搜索的递一个参数
     * 2、$size: 每页显示多少数据
     * 3、$fields = 搜索哪些字段，后面的^数字 是权重的分配
     * 4、$page 利用thinkphp的Bootstrap 来创建分页
     */
    public function product()
    {
        if (Request::isGet()) {
            $builder = new elSearch();
            $pages = input('page', 1);
            $size = 12;//后面加配置里去
            $builder->paginate($pages, $size);
            $builder->Index('products_' . $this->language_id);
            $search = input('key', '');
            if (!empty($search)) {
                $keywords = array_filter(explode(' ', $search));
                $fields = ['name^4', 'url_title^3', 'model^2', 'seo_title^1', 'keywords^1', 'description', 'features'];
                $builder->keywords($keywords, $fields);
            }
            $result = $builder->Client()->search($builder->getParams());
            $total = $result['hits']['total']['value'];
            $page = Bootstrap::make($result['hits']['hits'], $size, $pages, $total, false, ['var_page' => 'page','path'=>'/'.$this->code.'/search/product','query'=>['key'=>$search]]);
            $this->assign('data', $result['hits']['hits']);
            $this->assign('page', $page);
            $this->assign('search',$search);
            $this->assign('count',$total);
            return $this->fetch($this->template . '/search/product.html');
        }
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