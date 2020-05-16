<?php
/**
 * Created by PhpStorm.
 * User: web jinxiu89@163.com
 * Date: 2018/8/30
 * Time: 11:07
 * 命名规范：文件名首字母大写
 * 类 首写字母大写
 * 函数 驼峰命名  如getInfo
 */

namespace app\wavlink\controller\Service;
use app\common\model\Cate as CateModel;
use app\wavlink\controller\BaseAdmin;

class Cate extends BaseAdmin
{
    public function index()
    {
        $result = (new CateModel())->getCateByStatus($status=0);
        if (request()->isAjax()) {
            $data=input('post.');
            $data['status']=1;
            $validate = Validate('Cate');
            if(!$validate->scene('add')->check($data)){
                return show(0,'','','','',$validate->getError());
            }else{
                $cate=new CateModel();
                if($cate->save($data)){
                    return show(1,'','','','', '添加成功');
                }else{
                    return show(0,'','','','', '添加失败');
                }
            }
        }
        return $this->fetch(
            '',
            [
                'result'=>$result['data'],
                'count'=>$result['count'],
            ]
        );
    }

    public function add()
    {
        return $this->fetch();
    }
}