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
use think\exception\HttpException;
use app\common\model\ServiceCategory as ServiceCategoryModel;
use app\common\helper\Search as elSearch;

class Search extends Base
{

    /**\
     * 搜索，先展示产品
     */
    public function index()
    {
       $data=ProductModel::allData($this->language_id);
       print_r($data);

    }


    //产品搜索
    public function product()
    {
        $key = input('get.key', '', 'trim');
        if ($key == '' || empty($key)) {
            return $this->fetch('', [
                'name' => $key,
                'count' => 0
            ]);
        } else {
            $result = (new ProductModel())->frontendGetSelectProduct($key, $this->code);
            $product = $result['data'];
            $count = $result['count'];
            $page = $product->render();
            return $this->fetch($this->template . '/search/product.html', [
                'result' => $product,
                'count' => $count,
                'name' => $key,
                'page' => $page,
            ]);
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