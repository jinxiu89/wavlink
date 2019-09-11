<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/3/7
 * Time: 14:12
 */
namespace app\zh_cn\controller;


use think\exception\HttpException;
use app\common\model\Drivers as DriversModel;
use app\common\model\ServiceCategory as ServiceCategoryModel;
class Drivers extends Base
{

    protected $beforeActionList = [
        'cate' => ['only' => 'index,category']
    ];
//    public function first(){
//        $name = request()->controller();
//        $parent = ServiceCategoryModel::getTopCategory($this->code,$name);
//        $cate = ServiceCategoryModel::getSecondCategory($this->code);
//        $childs = array_chunk($cate,3);
//        $this->assign('parent',$parent);
//        $this->assign('childs',$childs); //html嵌套循环三维数组
//    }
    //驱动下载首页
    public function index()
    {
       //获取Drivers分类信息
        $parent = ServiceCategoryModel::getTopCategory($this->code, 'drivers');
        //获取所有驱动下载列表
        $drivers = (new DriversModel())->getDriversByCategoryId($this->code,'');
        $page = $drivers->render();
        return $this->fetch($this->template.'/drivers/index.html', [
            'drivers' => $drivers,
            'page'    => $page,
            'parent'  => $parent,
            'name'    => '',
        ]);
    }

    //获取选择分类下的驱动列表
    /**
     * @param string $category
     * @return \think\response\View
     */
    public function category($category = "")
    {
        if (empty($category) || !isset($category)) {
            abort(404);
        }
        //获取选择的子分类信息
        $parent =ServiceCategoryModel::getCategoryIdByName($this->code,$category);
        if (empty($parent)){
            throw new HttpException(404);
        }else{
            //获取选择的分类下的驱动列表
            $drivers = (new DriversModel())->getDriversByCategoryId($this->code, $parent['id']);
            return $this->fetch($this->template.'/drivers/index.html', [
                'drivers' => $drivers,
                'parent' => $parent,
                'name'    =>$parent['name'],

            ]);
        }
    }

    public function details($drivers = "")
    {
        if (!isset($drivers) || empty($drivers)) {
            abort(404);
        }
        $result = (new DriversModel())->getDetailsByUrlTitle($this->code, $drivers);
        $result_models = $result["models"];
        $models = explode(",",$result_models);
        if (!empty($result)) {
            return $this->fetch($this->template.'/drivers/details.html', [
                'result' => $result,
                'models' => $models
            ]);
        } else {
            abort(404);
        }
    }


    /**
     * @return mixed|string
     * 搜索提示
     */
//    public function select()
//    {
//        $keyword = $_GET['keyword'];
//        $drivers = model("Drivers")->selectByKeyword($this->code, $keyword);
//        print_r($drivers);
//        if (!empty($keyword) && !empty($drivers)) {
//            $this->assign('drivers',$drivers);
//            return $this->fetch('Drivers/index');
//        } else {
//            return '没有数据';
//        }
//    }
}