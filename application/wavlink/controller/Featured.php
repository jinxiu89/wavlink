<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/18
 * Time: 16:14
 * 推荐位管理
 */
namespace app\wavlink\controller;

use app\common\model\Featured as FeaturedModel;
use think\Request;
use app\wavlink\validate\Featured as FeaturedValidate;
class Featured extends BaseAdmin
{

    public function index() {
        $result = (new FeaturedModel())->getDataByOrder('');
        return $this->fetch('', [
            'featured' => $result['data'],
            'counts' => $result['count'],
        ]);
    }

    public function select() {
        return view();
    }

    public function add() {
        return $this->fetch();
    }

    public function save(Request $request) {
        if (request()->isAjax()){
            $data = $request::instance()->post();
            (new FeaturedValidate())->goCheck();
            if (!empty($data['id'])){
                return $this->update($data);
            }
            $res = (new FeaturedModel())->add($data);
            if ($res) {
                return show(1,'','','','', '添加成功');
            } else {
                return show(1,'','','','', '添加失败');
            }
        }
    }

    public function edit($id) {
        if (intval($id) < 0) {
            return show(0, 'error', 'ID非法');
        }
        $featured = FeaturedModel::get($id);
        return $this->fetch('', [
            'featured' => $featured,
        ]);
    }
//    //单个删除
//    public function del(Request $request){
//        $id = $request::instance()->param();
//        if (empty($id)){
//            return show(0,'','数据不存在');
//        }
//        $res = \app\common\model\Featured::destroy($id);
//        if ($res){
//            return show(1,'','删除成功');
//        }else{
//            return show(0,'','删除失败');
//        }
//    }
//    //批量删除
//    public function delAll(Request $request){
//        $ids = $request::instance()->post();
//        if (!is_array($ids)){
//            return show(0,'','数据异常');
//        }
//        try{
//            foreach ($ids as $k => $v){
//                if (\app\common\model\Featured::get($k)){
//                    \app\common\model\Featured::destroy($k);
//                }else{
//                    return show(0,'','删除错误');
//                }
//            }
//            return show(1,'','批量删除成功');
//        }catch (\Exception $e){
//            return show(0,'',$e->getMessage());
//        }
//    }
}
