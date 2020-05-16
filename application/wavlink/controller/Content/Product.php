<?php

namespace app\wavlink\controller\Content;

/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:37
 * 功能归类
 */

use app\common\model\Content\Product as ProductModel;
use app\common\model\Content\Category as CategoryModel;
use app\common\model\Content\ShopLink;
use app\wavlink\validate\ListorderValidate;
use think\Exception;
use think\Facade\Request;
use app\wavlink\validate\Product as ProductValidate;
use app\wavlink\validate\ShopUrl as ShopUrlValidate;
use think\paginator\driver\Bootstrap;
use app\wavlink\controller\BaseAdmin;
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
     * 概述：
     * 后台的产品列表页，支持搜索和筛选，由于当初设计时没有分开设计，导致本控制器稍微长了一点，但思路是正确的，
     * 分为3个情况:
     * 1、什么都不选的情况下，load出所有指定语言的产品，按照条件排序
     * 2、用户选择了筛选条件，则去筛选分支
     * 3、用户按型号，名称等条件搜索时，去搜索分支
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $data = input('get.');
        $category = (new CategoryModel())->getAllCategory($this->currentLanguage['id']);
        if (!empty($data) and !empty($data['category_id'])) {//当选择分类时
            try {
                $response = (new ProductModel())->getDataByCategory($data['category_id'], $this->currentLanguage['id']);
                $result = array_unique($response, SORT_REGULAR);//去重
                $count = count($result);//计数
                $pages = input('page', 1);//有分页的情况下拿分页
                $size = 12;//后面加配置里去， 每页显示几个数据
                $page_options = ['var_page' => 'page', 'path' => '/wavlink/product/index.html', 'query' => ['category_id' => $data['category_id']]];//分页选项
                $page = Bootstrap::make($result, $size, $pages, $count, false, $page_options);
                $this->assign('product', array_slice($result, ($pages - 1) * $size, $size));//通过这一条来给前台分页
                $this->assign('counts', $count);
                $this->assign('category_id', $data['category_id']);
                $this->assign('page', $page);
            } catch (\Exception $exception) {
                $this->error($exception->getMessage());
            };
        } else if (!empty($data) and !empty($data['name'])) {//按名称型号搜索时
            $response = (new ProductModel())->getDataByName($data['name'], $this->currentLanguage['id']);
            $page = $response['data']->render();
            $this->assign('product', $response['data']);
            $this->assign('counts', $response['count']);
            $this->assign('page', $page);
            $this->assign('name', $data['name']);
            $this->assign('category_id', '');
        } else { //什么都不选时
            $product = ProductModel::getDataByStatus(1, $this->currentLanguage['id']);
            $page = $product['data']->render();
            $this->assign('product', $product['data']);
            $this->assign('counts', $product['count']);
            $this->assign('page', $page);
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
        //获取语言
        //根据语言id获取语言分类
        $categorys = (new CategoryModel())->getChildsCategory($this->currentLanguage['id']);
        return $this->fetch('', [
            'language_id' => $this->currentLanguage['id'],
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
                        if ((new ProductModel())->productSave($data)) {
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
                        if ((new ProductModel())->productSave($data)) {
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

    /**
     * @param int $id
     * @return mixed
     * 产品编辑
     * 注意点： $this->currentLanguage['id'] 是全局的语言，在base控制器中有设置，并且在登录的时候 这个值就被写入cookie 供后台使用
     *
     */
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
     *
     * @return mixed/
     * 查询该产品有多少个销售地址
     * product_id 是唯一条件，并且必须存在
     */
    public function shop_link()
    {
        if ($this->request->isGet()) {
            $product_id = $this->request->param('product_id', '', 'trim,intval');
            try {
                $query = ShopLink::where('product_id', '=', $product_id);
                $data = $query->field('id,name,url,price,create_time,update_time')->select();
                $count = $query->count();
                $this->assign('data', $data);
                $this->assign('count', $count);
            } catch (Exception $exception) {

            }
            $this->assign('product_id', $product_id);
            return $this->fetch();
        }
    }

    /**
     *
     */
    public function add_shop_url()
    {
        if ($this->request->isGet()) {
            $product_id = $this->request->param('product_id', '', 'trim,intval');
            return $this->fetch('', ['product_id' => $product_id]);
        }
    }

    /**
     *
     */
    public function edit_shop_url()
    {
        if ($this->request->isGet()) {
            $id = $this->request->param('id', '', 'trim,intval');
            try {
                $data = ShopLink::get($id);
                $this->assign('data', $data);
            } catch (Exception $exception) {

            }
            return $this->fetch();
        }
    }

    public function del_shop_url()
    {
        if ($this->request->isPost()) {
            $id = $this->request->param('id', '', 'trim,intval');
            try {
                $result = ShopLink::where(['id' => $id])->delete();
                if ($result) {
                    return show(1, '', '', '', '', '删除成功');
                }
                return show(0, '', '', '', '', '删除失败');
            } catch (Exception $exception) {
                return show(0, '', '', '', '', $exception->getMessage());
            }
        }
    }

    public function save_shop_url()
    {
        if ($this->request->isPost()) {
            $data = input('post.');
            $validate = new ShopUrlValidate();
            if (isset($data['id']) || !empty($data['id'])) {
                //更新操作
                if ($validate->scene('edit')->check($data)) {
                    try {
                        if ((new ShopLink())->allowField(true)->save($data, ['id' => $data['id']])) return show(1, '', '', '', '', '更新成功');
                        return show(0, '', '', '', '', '添加失败');
                    } catch (Exception $exception) {
                        return show(0, '', '', '', '', $exception->getMessage());
                    }
                } else {
                    return show(0, '', '', '', '', $validate->getError());
                }

            } else {
                //add操作
                if ($validate->scene('add')->check($data)) {
                    try {
                        if ((new ShopLink())->allowField(true)->save($data)) return show(1, '', '', '', '', '添加成功');
                        return show(0, '', '', '', '', '添加失败');
                    } catch (Exception $exception) {
                        return show(0, '', '', '', '', $exception->getMessage());
                    }
                } else {
                    return show(0, '', '', '', '', $validate->getError());
                }
            }
        }
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