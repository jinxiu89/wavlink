<?php
/**
 * Created by PhpStorm.
 * User: web jinxiu89@163.com
 * Date: 2018/8/29
 * Time: 11:11
 * 命名规范：文件名首字母大写
 * 类 首写字母大写
 * 函数 驼峰命名  如getInfo
 */

namespace app\wavlink\controller\Service;

use app\common\model\Soft as SoftModel;
use app\common\model\Model;
use app\wavlink\controller\BaseAdmin;
use app\wavlink\validate\Soft as SoftValidate;

class Soft extends BaseAdmin
{
    /**
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * @throws \think\Exception
     */
    public function index()
    {
        $soft = new SoftModel();
        $result = $soft->getDateByStatus();
        if (request()->isAjax()) {
            $data = input('post.');
            $data['status'] = 1;
            if (!isset($data['soft_id']) || $data['soft_id'] == "") {
                $validate = new SoftValidate();
                if (!$validate->scene('add')->check($data)) {
                    return [0, $validate->getError()];
                }
                if ((new SoftModel())->allowField(true)->save($data)) {
                    return [1, '本体数据保存成功！'];
                }
            }
            $soft = SoftModel::get($data['soft_id']);
            if (!is_array($data['model_id']) || $data['model_id'][0] == "" || !isset($data['model_id'])) {
                if ($soft->allowField(true)->save($data)) {
                    return [1, "保存成功!"];
                } else {
                    return [1, "保存成功，但是没有选择支持的硬件信息"];
                }
            } else {
                $models = [];
                foreach ($data['model_id'] as $item) {
                    $models[] = intval($item);
                }
                if ($soft->models()->saveAll($models)) {
                    return [1, "所有的保存成功！"];
                }
            }
            return [0, "未知问题！"];
        }
        return $this->fetch(
            '', [
                'result' => $result['data'],
                'count' => $result['count'],

            ]
        );
    }

    public function add()
    {
        return $this->fetch('', [
        ]);
    }

    public function edit($id)
    {
        $result = SoftModel::get($id);
        $model = $result->models;
        //ids 是存在数据库里的 关系表的model_id 一维数组
        $ids = [];
        foreach ($model as $item) {
            $ids[] = $item->pivot->toArray()['model_id'];
        }
        if (request()->isAjax()) {
            $data = input('post.');
            if (!isset($data['model_id']) || !is_array($data['model_id']) || $data['model_id'][0] == "") {
                if ($result->allowField(true)->save($data)) {
                    return show(1, '', '', '', '', "保存数据成功！");
                } else {
                    return show(0, '', '', '', '', "保存数据成功！硬件必须留一个在上面哇！");
                }
            } else {
                $model_id = $data['model_id'];
                $validate = new SoftValidate();
                if (!$validate->scene('save')->check($data)) {
                    return show(0, '', '', '', '', $validate->getError());
                }
                unset($data['model_id']);
                $result->save($data);
                $models = [];
                foreach ($model_id as $v) {
                    $models[] = intval($v);
                }
                //删除中间表旧数据
                $result->models()->detach($ids);
                //保存中间表新数据
                if ($result->models()->saveAll($models)) {
                    return show(1, '', '', '', '', "保存数据成功！");
                }
            }
        }
        return $this->fetch(
            '', ['result' => $result, 'model' => $model]
        );
    }

    public function add_model()
    {
        $model = new Model();
        $data = $model->getDateByStatus($map=array('status'=>1));
        return $this->fetch('', ['data' => $data['data']]);
    }

    /***
     * 这个方法是
     * @param $id
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function edit_model($id)
    {
        $model = new Model();
        $data = $model->getDateByStatus($map=array('status'=>1));
        $soft = SoftModel::get($id);
        $models = $soft->models;
        $result_model = [];
        foreach ($models as $model) {
            $result_model[] = $model->pivot->toArray()['model_id'];
        }
        return $this->fetch('', [
            'data' => $data['data'],
            'result_model' => $result_model,
            'models' => $models
        ]);
    }

    public function saveID()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            $data['status'] = 1;
            $validate = new SoftValidate();
            if (!$validate->scene('saveID')->check($data)) {
                return show(0, '', '', '', '', $validate->getError());
            } else {
                $soft = new SoftModel();
                if ($soft->allowField(true)->save($data)) {
                    $result = $soft->where('ver', $data['ver'])->find();
                    return show(1, '', '', '', '', $result['id']);
                } else {
                    return show(0, '', '', '', '', '保存失败！');
                }
            }

        }
    }
}