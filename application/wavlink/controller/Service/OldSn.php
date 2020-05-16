<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/9/1
 * Time: 9:43
 */

namespace app\wavlink\controller\Service;

use app\common\model\Model;
use app\common\model\OldSn as OldSnModel;
use app\wavlink\controller\BaseAdmin;
use app\wavlink\validate\OldSn as OldSnValidate;

class OldSn extends BaseAdmin
{
    public function index()
    {
        $sn = new OldSnModel();
        $result = $sn->getDateByStatus();
        if (request()->isAjax()) {
            $data = input('post.');
            $data['status'] = 1;
            $data['prefix'] = strtoupper($data['prefix']);
            $validate = new OldSnValidate();
            if (!$validate->scene('add')->check($data)) {
                return show(0, '', '', '', '', $validate->getError());
            } else {
                if ($sn->save($data)) {
                    return show(1, '', '', '', '', '添加成功');
                } else {
                    return show(0, '', '', '', '', '添加失败');
                }
            }
        }
        return $this->fetch('', ['result' => $result['data'], 'count' => $result['count']]);
    }

    public function add()
    {
        $model = (new Model())->getDateByStatus();
        return $this->fetch('', ['model' => $model['data']]);
    }
}