<?php

namespace app\wavlink\controller;

/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:37
 */

use app\common\model\Product as ProductModel;
use app\common\model\Category as CategoryModel;
use app\wavlink\validate\ListorderValidate;
use think\Facade\Request;
use app\wavlink\validate\Product as ProductValidate;

/***
 * Class Product
 * @package app\wavlink\controller
 *
 */
Class Product extends BaseAdmin
{
    //产品列表，status=1
    /***
     * @return mixed
     * 产品列表
     */
    public function index()
    {
        $product = ProductModel::getDataByStatus(1, $this->currentLanguage['id']);
        $category = (new CategoryModel())->getAllCategory($this->currentLanguage['id']);
        $data = input('get.');
        if (!empty($data) and !empty($data['name'])) {
            try {
                $result = (new ProductModel())->getSelectProduct($data['name'], $data['category_id'], $this->currentLanguage['id']);
                $this->assign('product', $result['data']);
                $this->assign('counts', $result['count']);
                $this->assign('name', $data['name']);
                $this->assign('category_id', $data['category_id']);
            } catch (\Exception $exception) {
                $this->error($exception->getMessage());
            };
        } else {
            $this->assign('product', $product['data']);
            $this->assign('counts', $product['count']);
            $this->assign('name', '');
            $this->assign('category_id', '');
        }
        return $this->fetch('', [
            'category' => $category,
            'language_id' => $this->currentLanguage['id'],
        ]);
    }

    //回收站的产品的列表,status=-1
    public function product_recycle()
    {
        $result = ProductModel::getDataByStatus(-1, $this->currentLanguage['id']);
        return $this->fetch('', [
            'product' => $result['data'],
            'counts' => $result['count'],
        ]);
    }

    public function add()
    {
        $language_id = input('get.language_id');
        //获取语言
        //根据语言id获取语言分类
        $categorys = (new CategoryModel())->getChildsCategory($language_id);

        return $this->fetch('', [
            'language_id' => $language_id,
            'categorys' => $categorys,
        ]);
    }

    /**
     * 产品保存 更新验证器 和异常捕获
     */
    public function save()
    {
        //严格判断校验
        if (request()->isAjax()) {
            $data = input('post.');
            $data['clicks'] = 100;
            $validate = new ProductValidate();
            if (isset($data['id']) and !empty($data['id'])) {
                if ($validate->scene('edit')->check($data)) {
                    try {
                        $res = (new ProductModel())->productSave($data);
                        if ($res) {
                            return show(1, '', '', '', '', '更新成功');
                        } else {
                            return show(0, '', '', '', '', '添加失败');
                        }
                    } catch (\Exception $exception) {
                        return show(0, '', '', '', '', $exception->getMessage());
                    }
                }
            } else {
                if ($validate->scene('add')->check($data)) {
                    try {
                        $res = (new ProductModel())->productSave($data);
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
            return show(0, '', '', '', '', $validate->getError());
        }
    }

    public function product_edit($id = 0)
    {
        $id = $this->MustBePositiveInteger($id);
        $product = ProductModel::get($id);
        //获取语言
        //获取分类
        $categorys = (new CategoryModel())->getChildsCategory($this->currentLanguage['id']);
        $cateID = ProductModel::getProductCategory($id);
        return $this->fetch('', [
            'categorys' => $categorys,
            'language_id' => $this->currentLanguage['id'],
            'product' => $product,
            'cateID' => $cateID,
        ]);
    }

    /**
     * 排序功能开发
     * $data = [];
     * id,type,language_id 是必须的
     * map是可有可无的，额外条件查询
     */
    public function listorder()
    {
        if (request()->isAjax()) {
            $data = input('post.'); //id ,type ,language_id,map
            $validate = new ProductValidate();
            if (!$validate->scene('listorder')->check($data)) {
                return show(0, '排序失败', 'error', 'error', '', $validate->getError());
            }
            try {
                if ($data['type'] == "+") {
                    $data['listorder'] = $data['listorder'] + 2;
                }
                if ($data['type'] == "-") {
                    $data['listorder'] = $data['listorder'] - 2;
                }
                if ((new ProductModel())->allowField(true)->save($data, ['id' => $data['id']])) {
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

    public function mark()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            $validate = new ProductValidate();
            if (!$validate->scene('mark')->check($data)) {
                return show(0, '排序失败，数据库错误', 'error', 'error', '', $validate->getError());
            }
            try {
                $model = new ProductModel();
                $cates = $model::getProductCategory($data['id']);
                $cate = end($cates);
                $data['listorder'] = $model->getTopOrder($cate) + 1;
                $url = Request::header('referer');
                if ($model->save($data, ['id' => $data['id']])) {
                    return show(1, "success", '', '', $url, '置顶排序成功');
                }
                return show(0, '排序失败，数据库错误', 'error', 'error', '', "排序失败，未知错误");
            } catch (\Exception $exception) {
                return show(0, '排序失败，数据库错误', 'error', 'error', '', $exception->getMessage());
            }
        }
    }

    /**
     * @return array|void
     */
    public function byStatus()
    {
        $data = input('get.');
        $check['status'] = number_format($data['status']);
        $validate = new ProductValidate();
        if ($validate->scene('changeStatus')->check($data)) {
            try {
                if ((new ProductModel())->allowField(true)->save($data, ['id' => $data['id']])) {
                    return show(1, "success", '', '', '', '操作成功');
                }
                return show(0, "success", '', '', '', '操作失败！未知原因');
            } catch (\Exception $exception) {
                return show(0, "failed", '', '', '', $exception->getMessage());
            }
        }
        return show(0, "failed", '', '', '', $validate->getError());
    }

    //批量放回回收站
    public function allRecycle(Request $request)
    {
        $ids = $request::instance()->post();
        foreach ($ids as $k => $v) {
            if (ProductModel::get($k)) {
                //批量更新数据。
                (new ProductModel())->where('id', $k)->update(['status' => -1]);
                //批量更新排序
//                $this->obj->where('id', $k)->update(['listorder' => $k + 100]);
            } else {
                return show(0, '', '', '', '', '回收失败');
            }
        }
        return show(1, '', '', '', '', '批量回收成功');
    }

    public function sort()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            $validate = new ListorderValidate();
            if ($validate->scene('listorder')->check($data)) {
                try {
                    $res = (new ProductModel())
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