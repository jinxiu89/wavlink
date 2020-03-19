<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:37
 * 下载中心
 */

namespace app\wavlink\controller;

use app\common\model\Drivers as DriversModel;
use app\common\model\ServiceCategory as ServiceCategoryModel;
use app\wavlink\validate\Drivers as DriversValidate;
use app\wavlink\validate\ListorderValidate;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Facade\Request;
use think\Exception;
use think\response\View;

/**
 * Class Drivers
 * @package app\wavlink\controller
 */
Class Drivers extends BaseAdmin
{
    /***
     * @return mixed|View
     * @throws DbException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     */
    public function index()
    {
        if (request()->isPost()) {//搜索
            $data = input('post.name');
            $res = DriversModel::getSelectDrivers($data, $this->currentLanguage['id']);
            return view('', [
                'drivers' => $res['data'],
                'counts' => $res['count'],
                'language_id' => $this->currentLanguage['id']
            ]);
        }
        $result = DriversModel::getDataByStatus(1, $this->currentLanguage['id']);
        return $this->fetch('', [
            'drivers' => $result['data'],
            'counts' => $result['count'],
            'language_id' => $this->currentLanguage['id']
        ]);
    }

    /**
     * 添加下载页面
     */
    public function add()
    {
        //获取驱动的分类
        $category = ServiceCategoryModel::getSecondCategory($this->currentLanguage['id']);
        return $this->fetch('', [
            'category' => $category,
            'language_id' => $this->currentLanguage['id'],
        ]);
    }

    /**
     * 提交保存数据
     * @return array
     * 修复 数据库字段为空时 的500错误，利用try catch 捕获异常
     */
    public function save()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            $validate = new DriversValidate();
            if (isset($data['id']) || !empty($data['id'])) {
                try{
                    if ($validate->scene('edit')->check($data)) {
                        return $this->update($data);
                    } else {
                        return show(0, '', '', '', '', $validate->getError());
                    }
                }catch (\Exception $exception){
                    return show(0, '', '', '', '', $exception->getMessage());
                }
            } else {
                try{
                    if ($validate->scene('add')->check($data)) {
                        $res = (new DriversModel())->add($data);
                        if ($res) {
                            return show(1, '', '', '', '', '添加成功');
                        } else {
                            return show(0, '', '', '', '', '添加失败');
                        }
                    } else {
                        return show(0, '', '', '', '', $validate->getError());
                    }
                }catch (\Exception $exception){
                    return show(0, '', '', '', '', $exception->getMessage());
                }
            }
        }
    }

    /**
     * 编辑操作开发
     * @param int $id
     * @return mixed
     * @throws DbException
     */
    public function edit($id)
    {
        $id = $this->MustBePositiveInteger($id);
        //获取驱动的二级分类
        $category = ServiceCategoryModel::getSecondCategory($this->currentLanguage['id']);
        $drivers = DriversModel::get($id);
        return $this->fetch('', [
            'language_id' => $this->currentLanguage['id'],
            'drivers' => $drivers,
            'category' => $category,
        ]);
    }

    /**
     * 批量回收
     * @param Request $request
     * @return array
     */
    public function allRecycle(Request $request)
    {
        $ids = $request::instance()->post();
        if (!is_array($ids)) {
            return show(0, 'error', '', '', '', '数据错误');
        }
        try {
            foreach ($ids as $k => $v) {
                if (DriversModel::get($k)) {
                    model('Drivers')->where('id', $k)->update(['status' => -1]);
                } else {
                    return show(0, 'error', '', '', '', '回收失败');
                }
            }
            return show(1, 'success', '', '', '', '批量回收成功');
        } catch (Exception $e) {
            return show(0, 'error', '', '', '', $e->getMessage());
        }
    }

    /**
     * 回收下载列表开发
     */
    public function recycle()
    {
        $result = DriversModel::getDataByStatus(-1, $this->currentLanguage['id']);
        return $this->fetch('', [
            'drivers' => $result['data'],
            'counts' => $result['count'],
        ]);
    }

    /**
     * 排序功能开发
     * 默认 必须数据 id,type,language_id
     **type == 1 时 置底
     * type == 4 时 置顶
     * type == 3 时 上移
     * type == 2 时 下移
     */
    public function listorder()
    {
        if (request()->isAjax()) {
            $data = input('post.'); //id ,type ,language_id
            self::order(array_filter($data));
        } else {
            return show(0, '置顶失败，未知错误', 'error', 'error', '', '');
        }
    }

    /**
     * 改变状态
     */
    public function byStatus()
    {
        $data = input('get.');
        $validate = new DriversValidate();
        $model = new DriversModel();
        if ($validate->scene('changeStatus')->check($data)) {
            try {
                if ($model->allowField(true)->save($data, ['id' => $data['id']])) {
                    return show(1, "success", '', '', '', '操作成功');
                }
                return show(0, "success", '', '', '', '操作失败！未知原因');
            } catch (\Exception $exception) {
                return show(0, "failed", '', '', '', $exception->getMessage());
            }
        }
        return show(0, "failed", '', '', '', $validate->getError());
    }
    /**
     *
     */
    public function sort()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            $validate = new ListorderValidate();
            if ($validate->scene('listorder')->check($data)) {
                try {
                    $res = (new DriversModel())
                        ->allowField(true)
                        ->isUpdate(true)
                        ->save(
                            ['listorder' => $data['listorder']],
                            ['id' => $data['id']]
                        );
                    if ($res) {
                        return show(1, '操作成功', '', '', $_SERVER['HTTP_REFERER'], '排序成功');
                    } else {
                        return show(0, '操作失败', '', '', $_SERVER['HTTP_REFERER'], '排序失败，未知原因');
                    }
                } catch (\Exception $exception) {
                    return show(0, '操作失败', '', '', $_SERVER['HTTP_REFERER'], $exception->getMessage());
                }
            }
            return show(0, '操作失败', '', '', $_SERVER['HTTP_REFERER'], $validate->getError());
        }
    }
}