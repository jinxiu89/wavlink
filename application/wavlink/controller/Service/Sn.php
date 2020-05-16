<?php
/**
 * Created by PhpStorm.
 * User: web jinxiu89@163.com
 * Date: 2018/8/31
 * Time: 9:23
 * 命名规范：文件名首字母大写
 * 类 首写字母大写
 * 函数 驼峰命名  如getInfo
 */

namespace app\wavlink\controller\Service;

use app\common\model\Model;
use app\wavlink\controller\BaseAdmin;
use app\wavlink\validate\Sn as SnValidate;
use app\common\model\Sn as SnModel;

class Sn extends BaseAdmin
{
    public function index()
    {
        $sn=new SnModel();
        $result=$sn->getDateByStatus();
        if (request()->isAjax()) {
            $data = input('post.');
            $data['status'] = 1;
            $data['prefix'] = strtoupper($data['prefix']);
            $validate = new SnValidate();
            if (!$validate->scene('add')->check($data)) {
                return show(0, '', '', '', '', $validate->getError());
            } else {

                $result = $sn->save($data);
                if ($result) {
                    return show(1, '', '', '', '', '添加成功');
                } else {
                    return show(0, '', '', '', '', '添加失败');
                }
            }
        }
        return $this->fetch(
            '',['result'=>$result['data'],'count'=>$result['count']]
        );
    }

    public function add()
    {
        $model = (new Model())->getDateByStatus();
        return $this->fetch(
            '', ['model' => $model['data']]
        );
    }
}