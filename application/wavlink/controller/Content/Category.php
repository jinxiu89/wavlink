<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:37
 */

namespace app\wavlink\controller\Content;

use app\common\model\Content\Category as CategoryModel;
use app\wavlink\validate\Category as CategoryValidate;
use think\App;
use think\facade\Request;
use app\wavlink\controller\BaseAdmin;

/***
 * Class Category
 * @package app\wavlink\controller
 * 产品分类功归类 时间：2020.05.16
 */
class Category extends BaseAdmin
{
    protected $validate;
    protected $model;

    /**
     * Category constructor.
     * @param App|null $app
     */
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->validate = new CategoryValidate();
        $this->model = new CategoryModel();
    }

    /***
     * @return mixed
     * 20190912 更新
     * 1. 语言ID 在登录时就记录下来当前管理的语言
     * 所有的语言记录用于数据过滤
     */
    public function index()
    {
        $parentId = input('get.parent_id', '0', 'intval');
        $result = $this->model->getCategory($parentId, $this->currentLanguage['id']);
        return $this->fetch('', [
            'category' => $result['data'],
            'counts' => $result['count'],
        ]);
    }

    public function add()
    {
        if (input('get.parent_id')) {
            //如有存在parent_id ,就是添加子分类
            $category_id = input('get.parent_id', 1, 'intval');
//            $category = CategoryModel::get(['status' => 1, 'id' => $category_id, 'language_id' => $language_id]);
            $this->assign('parent_id', $category_id);
        } else {
            //添加一级分类
            $this->assign('parent_id', 0);
        }
        return $this->fetch('', [
            'language_id' => $this->currentLanguage['id'],
        ]);
    }

    /***
     * @return array|void
     * 重要备注：本例的is_parent 规则为 0 为子分类 1 为目录，切记，切记
     */
    public function save()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            $category = CategoryModel::get($data['parent_id']);
            if (isset($data['id']) and !empty($data['id'])) {
                if ($this->validate->scene('edit')->check($data)) {
                    if ($data['id'] == $data['parent_id']) {
                        return show(0, '', '不能编辑在自己名下');
                    } else {
                        if ($data['parent_id'] != 0) $data['level'] = getProductCategoryLevel($data['parent_id']); //产品分类和服务分两类确实有所不同
                        if ($data['parent_id'] != 0) $data['path'] = $category->path . $data['parent_id'] . '-';
                        return $this->update($data);
                    }
                }
            } else {
                if ($this->validate->scene('add')->check($data)) {
                    $data['path'] = $category->path . $data['parent_id'] . '-';
                    try {
                        $res = (new CategoryModel())->add($data);
                        if ($res) {
                            return show(1, '', '', '', '', '添加成功');
                        } else {
                            return show(0, '', '', '', '', '添加失败');
                        }
                    } catch (\Exception $exception) {
                        return show(0, '', '', '', '', $exception->getMessage());
                    }
                }
            }
            return show(0, '', '', '', '', $this->validate->getError());
        }
    }

    /**
     * 编辑分类页面
     * @param int $id
     * @return array|mixed
     */
    public function edit($id = 0)
    {
        $id = $this->MustBePositiveInteger($id);
        $category = $this->model->get($id);
        if ($category['parent_id'] > 0) {
            $cate = $this->model->where('level', '<', 2)
                ->all(['status' => 1, 'language_id' => $this->currentLanguage['id']]);
            $tree = \app\common\helper\Category::toLevel($cate->toArray(), '&nbsp;&nbsp;');
            $this->assign('cate', $tree);
        } else {
            $this->assign('parent_id', 0);
        }
        return $this->fetch('', [
            'category' => $category,
            'language_id' => $this->currentLanguage['id'],
        ]);
    }

    /**
     * 排序功能开发
     */
    public function listorder()
    {
        if (request()->isAjax()) {
            $data = input('post.'); //id ,type ,language_id,map
            if (!$this->validate->scene('listorder')->check($data)) {
                return show(0, '排序失败', 'error', 'error', '', $this->validate->getError());
            }
            try {
                if ($data['type'] == "+") {
                    $data['listorder'] = $data['listorder'] + 2;
                }
                if ($data['type'] == "-") {
                    $data['listorder'] = $data['listorder'] - 2;
                }
                if ($this->model->allowField(true)->save($data, ['id' => $data['id']])) {
                    return show(1, '排序成功', 'error', 'error', '', "排序成功");
                }
                return show(0, '排序失败，未知错误', 'error', 'error', '', "排序失败，未知错误");
            } catch (\Exception $exception) {
                return show(0, '排序失败，数据库错误', 'error', 'error', '', $exception->getMessage());
            }
        } else {
            return show(0, '置顶失败，未知错误', 'error', 'error', '', '');
        }
    }

    /**
     * 排序,针对
     */
    public function sort()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            if ($this->validate->scene('listorder')->check($data)) {
                try {
                    $res = $this->model
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
            return show(0, '操作失败', '', '', $_SERVER['HTTP_REFERER'], $this->validate->getError());
        }
    }

    /**
     * 改变状态，当该分类存在产品或者有子分类时不能
     */
    public function byStatus()
    {
        $data = input('get.');
        $check['status'] = number_format($data['status']);
        if ($this->validate->scene('changeStatus')->check($data)) {
            try {
                $result = $this->model->checkData($data['id']);
                if (true != $result) {
                    return show(0, "failed", '', '', '', $result);
                }
                if ($this->model->allowField(true)->save($data, ['id' => $data['id']])) {
                    return show(1, "success", '', '', '', '操作成功');
                }
                return show(0, "success", '', '', '', '操作失败！未知原因');
            } catch (\Exception $exception) {
                return show(0, "failed", '', '', '', $exception->getMessage());
            }
        }
//        return show(0, "failed", '', '', '', $this->validate->getError());
    }


    public function del()
    {
        $id = input('get.id');
        if (!$this->validate->scene('del')->check(['id' => $id])) return show(0, 'error', $this->validate->getError());
        //从Category找是否存在子分类
        $result = $this->model->checkData($id);
        if ($result === true) {
            //删除
            try {
                $res = CategoryModel::destroy($id);
                if ($res) {
                    return show(1, 'success', '', '', '', '删除成功');
                } else {
                    return show(0, 'failed', '', '', '', '删除失败,位置错误');
                }
            } catch (\Exception $e) {
                return show(0, 'failed', '', '', '', $e->getMessage());
            }
        } else {
            return show(0, 'failed', '', '', '', $result);
        }
    }

//批量放回回收站
    public function allRecycle(Request $request)
    {
        try {
            $ids = $request::instance()->post();
            foreach ($ids as $k => $v) {
                if (CategoryModel::get($k)) {
                    //批量更新数据。
                    (new CategoryModel())->where('id', $k)->update(['status' => -1]);
                    //批量更新排序
//                    (new CategoryModel())->where('id', $k)->update(['listorder' => $k + 100]);
                } else {
                    return show(0, '', '回收失败');
                }
            }
            return show(1, '', '批量回收成功');
        } catch (\Exception $e) {
            return show(0, $e->getMessage(), '', '', '');
        }

    }
}