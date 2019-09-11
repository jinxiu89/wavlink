<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/3/7
 * Time: 17:02
 */
namespace app\zh_cn\controller;

use app\common\model\Drivers as DriversModel;
use app\common\model\Product as ProductModel;
use app\lib\exception\BannerMissException;
use think\Collection;
use think\Exception;
use think\exception\HttpException;
use app\common\model\ServiceCategory as ServiceCategoryModel;
use app\common\model\Faq as FaqModel;

class Search extends Base
{
    /**\
     * 搜索，先展示产品
     */
    public function index() {
//        $name = $_GET['key'];
//        if (empty($name)){
//            $this->error(lang('key_empty'));
//        }
//        $result = model("Product")->getSelectProduct($name,$this->code);
//        $product=$result['data'];
//        $count=$result['count'];
//        return $this->fetch('', [
//            'result' => $product,
//            'count'  => $count,
//            'name'    => $name
//        ]);
        if (config('app_debug')) {
            return '';
        } else {
            throw new HttpException(404);
        }
    }

    //产品搜索
    public function product() {
        $key = input('get.key','','trim');
        if ($key == '' || empty($key)) {
            return $this->fetch($this->template.'/search/product.html', [
                'name' => $key,
                'count' => 0
            ]);
        } else {
            $result = (new ProductModel())->frontendGetSelectProduct($key, $this->code);
            $product = $result['data'];
            $count = $result['count'];
            $page = $product->render();
            return $this->fetch($this->template.'/search/product.html', [
                'result' => $product,
                'count' => $count,
                'name' => $key,
                'page' => $page,
            ]);
        }
    }
    //产品联想搜索
    public function opProduct(){
        if (request()->isAjax()) {
            $key = input('get.key','','trim');
            $data = (new ProductModel())->opSelectProduct($key, $this->code,8);
            if (!empty($data)) {
                $searchList=[];
                foreach ($data as $k) {
                    $res = [
                        'name' => $k['name'],
                        'model' => $k['model'],
                    ];
                    $searchList[] = $res;
                }
                return json($searchList);
            }
        }else{
            abort(404);
        }
    }

    //驱动搜索
    public function drivers() {
        $key = input('get.key','','trim');
        if ($key == '' || empty($key)) {
            return $this->fetch($this->template.'/search/drivers.html', [
                'count' => '0',
                'result' => '',
                'name' => ''
            ]);
        } else {
            $res = (new DriversModel)->getSelectDrivers($key, $this->code);
            $result = ModelsArr($res['data'], 'models', 'modelsGroup');
            $page = $result->render();
            return $this->fetch($this->template.'/search/drivers.html', [
                'result' => $result,
                'count' => $res['count'],
                'name' => $key,
                'page' => $page,
            ]);
        }
    }

    //faq搜索
    public function faq() {
        $key = input('get.key','','trim');
        //获取一级faq分类
        $parent = ServiceCategoryModel::getTopCategory($this->code, 'faq');
        $cate = ServiceCategoryModel::getSecondCategory($this->code, 'faq');
        $res = (new FaqModel())->getSelectFaq($this->code, $key);
        if ($key == '' || empty($key) || !$res) {
            $this->assign('faq','');
            $this->assign('count',0);
        } else {
            $this->assign('faq',$res['data']);
            $this->assign('count',$res['count']);
        }
        $this->assign('parent', $parent);
        $this->assign('cate', $cate);
        $this->assign('name', $key);
        return $this->fetch($this->template.'/search/faq.html');
    }
}