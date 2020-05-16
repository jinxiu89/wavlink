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

use app\common\model\Model as prdModel;
use app\wavlink\controller\BaseAdmin;
use app\wavlink\validate\Model as ModelValidate;
use app\common\model\Cate as CateMode;
use app\common\model\Soft;

class Model extends BaseAdmin
{
    public function index()
    {
        $result = ((new prdModel())->getDateByStatus());

        if (request()->isAjax()) {
            $data = input('post.');
            $data['status'] = 1;
            $data['code'] = strtoupper($data['code']);
            if (!isset($data['model_id']) || $data['model_id'] == "") {
                $validate = new ModelValidate();
                if (!$validate->scene('add')->check($data)) {
                    return [0, $validate->getError()];
                }
                if ((new prdModel())->allowField(true)->save($data)) {
                    return [1, '本体数据保存成功！'];
                }
            }
            $model = prdModel::get($data['model_id']);
            if (!is_array($data['soft_id']) || $data['soft_id'][0] == "" || !isset($data['soft_id'])) {
                if ($model->allowField(true)->save($data)) {
                    return [1, "保存成功！"];
                } else {
                    return [1, "保存成功,没有选择软件版本"];
                }
            } else {
                $soft = [];
                foreach ($data['soft_id'] as $item) {
                    $soft[] = intval($item);
                }
                if ($model->soft()->saveAll($soft)) {
                    return [1, "保存成功"];
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

    public function add_soft()
    {
        $result = (new Soft())->getDateByStatus($map=array('status'=>1));
        return $this->fetch('', ['data' => $result['data']]);
    }

    public function edit_soft($id)
    {
        $data = (new Soft())->getDateByStatus($map=array('status'=>1));//所有的软件项目列表
        $models = prdModel::get($id);
        $soft = $models->soft;
        $result_soft = [];
        foreach ($soft as $item) {
            $result_soft[] = $item->pivot->toArray()['soft_id'];
        }
        return $this->fetch('', [
            'data' => $data['data'],
            'result_soft' => $result_soft,
            'soft' => $soft
        ]);
    }

    public function add()
    {
        $result = ((new CateMode())->getCateByStatus());
        return $this->fetch('', [
            'result' => $result['data'],
        ]);
    }

    /***
     * @param $id
     * @return mixed|void
     * @throws \think\exception\DbException
     */
    public function edit($id)
    {
        $cate = ((new CateMode())->getCateByStatus());
        $result = prdModel::get($id);
        $soft = $result->soft;
        //ids 是存在数据库里的 关系表的model_id 一维数组
        $ids = [];
        foreach ($soft as $item) {
            $ids[] = $item->pivot->toArray()['soft_id'];
        }
        if (request()->isAjax()) {
            $data = input('post.');//获取到数据
            $result->soft()->detach($ids);//先删除原有数据，这个百分百不出戳的，
            if (!isset($data['soft_id']) || !is_array($data['soft_id']) || $data['soft_id'][0] == "") {
                if ($result->allowField(true)->save($data)) {
                    return show(1, '', '', '', '', "保存数据成功！");
                } else {
                    return show(0, '', '', '', '', "保存数据成功！软件必须留一个在上面");
                }
            } else {
                $soft_id = $data['soft_id'];//把软件ID摘出来放在soft_id这个框里
                //把软件ID 转成整形，这里没有对穿的值做严格测试，因为这个地方人为修改不了
                $softData = [];
                foreach ($soft_id as $item) {
                    $softData[] = intval($item);
                }
                //丢弃掉数据里的soft_id数组
                unset($data['soft_id']);
                $data['code'] = strtoupper($data['code']);
                $result->allowField(true)->save($data);//这个地方是保存本体数据的，如果不修改保存的话会出现false
                if ($result->soft()->saveAll($softData)) {
                    return show(1, '', '', '', '', "保存数据成功！");
                } else {
                    return show(0, '', '', '', '', '保存数据失败！');
                }
            }
        }
        return $this->fetch('', [
            'result' => $result,
            'soft' => $soft,
            'cate' => $cate['data']
        ]);
    }

    public function save()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            $id = $data['id'];
            $result = prdModel::get($id);
            $soft = $result->soft;
            $ids = [];
            foreach ($soft as $item) {
                $ids[] = $item->pivot->toArray()['soft_id'];
            }
            $soft_id = $data['soft_id'];
            $validate = new ModelValidate();
            if (!$validate->scene('save')->check($data)) {
                return show(0, '', '', '', '', $validate->getError());
            }
            $result->save($data);
            $softData = [];
            foreach ($soft_id as $v) {
                $softData[] = intval($v);
            }
            //删除中间表旧数据
            $result->soft()->detach($ids);
            //保存中间表新数据
            if ($result->soft()->saveAll($softData)) {
                return show(1, '', '', '', '', "保存数据成功！");
            }

        }
    }

    public function saveId()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            $data['status'] = 1;
            $validate = new ModelValidate();
            if (!$validate->scene('add')->check($data)) {
                return show(0, '', '', '', '', $validate->getError());
            } else {
                $model = new prdModel();
                if ($model->allowField(true)->save($data)) {
                    $result = $model->where('code', $data['code'])->find();
                    return show(1, '', '', '', '', $result['id']);
                } else {
                    return show(0, '', '', '', '', '保存失败！');
                }
            }
        }
    }
}