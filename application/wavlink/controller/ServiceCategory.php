<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/11/6
 * Time: 11:37
 * 下载分类
 */

namespace app\wavlink\controller;

use app\common\model\ServiceCategory as ServiceCategoryModel;
use app\wavlink\validate\ServiceCategory as ServiceCategoryValidate;
use app\wavlink\validate\UrlTitleMustBeOnly;
use think\Facade\Request;

class ServiceCategory extends BaseAdmin
{
    /***
     * 1 MustBePositiveInteger  判断输入的语言ID是否是正整数
     * 2、getServiceCategory根据语言ID来获取分类
     * @return mixed
     *
     *
     */
    public function index()
    {
        $parentId = input('get.parent_id', 0, 'intval');
        $category = (new ServiceCategoryModel())->getServiceCategory($parentId, $this->currentLanguage['id']);
        return $this->fetch('', [
            'category' => $category['data'],
            'counts' => $category['count'],
            'language_id' => $this->currentLanguage['id']
        ]);
    }

    public function add()
    {
        //这是从服务分类添加的
        if (input('get.parent_id')) {
            //服务分类给父分类添加子分类
            $category_id = input('get.parent_id');
            $category = ServiceCategoryModel::get(['status' => 1, 'id' => $category_id, 'language_id' => $this->currentLanguage['id']]);
            //只添加两级分类。
            if (intval($category['level']) >= 3) {
                return '别添加了！返回一级栏目再添加吧';
            }
            $this->assign('parent_id', $category_id);
        } else {
            if (input('get.con')) {
                // 从其他列表添加对应的分类栏目
                $con = input('get.con');
                $category = ServiceCategoryModel::getCategoryIdByName($this->currentLanguage['id'], $con);
                $this->assign('parent_id', $category['id']);
            } else {
                //添加一级栏目
                $this->assign('parent_id', 0);
            }
        }
        $this->assign('language_id', $this->currentLanguage['id']);
        return $this->fetch();
    }

    /**
     * @param Request $request
     * @return array
     * 提交保存操作
     * @throws \app\lib\exception\ParameterException
     */
    public function save(Request $request)
    {
        if (request()->isAjax()) {
            (new ServiceCategoryValidate())->goCheck();
            (new UrlTitleMustBeOnly())->goCheck();
            $data = $request::instance()->post();
            if ($data['level'] == '') {
                $data['level'] = intval(1);
            }
            if (!empty($data['id'])) {
                if ($data['id'] == $data['parent_id']) {
                    return show(0, '', '不能编辑在自己名下');
                } else {
                    return $this->update($data);
                }
            }
            $res = (new ServiceCategoryModel())->add($data);
            if ($res) {
                return show(1, '', '', '', '', '添加成功');
            } else {
                return show(1, '', '', '', '', '添加失败');
            }
        }
    }

    /**
     * @param int $id
     * @return array|mixed 编辑页面开发
     * 编辑页面开发
     * @throws \think\exception\DbException
     */
    public function edit($id = 0)
    {
        $id = $this->MustBePositiveInteger($id);
        //获取当前数据
        $serviceCategory = ServiceCategoryModel::get($id);
        //传递参数id
        //如果是一级栏目 categorys不选择 parent_id = 0
        // 字段parent_id =0
        //否则得到categorys 选择一级栏目，parent_id 就是一级栏目的id
        //字段parent_id = $categorys['id']
        if ($serviceCategory['parent_id'] > 0) {
            //parent_id 大于0 说明它不是一级分类
            // 那就要得到相应语言的一级分类
            $categorys = ServiceCategoryModel::all(['status' => 1,
                'parent_id' => 0,
                'language_id' => $this->currentLanguage['id']
            ]);
            $this->assign('categorys', $categorys);
        } else {
            //parent_id 不是大于0的
            //它就是一级栏目，无法编辑到别的栏目去
            $parent_id = 0;
            $this->assign('parent_id', $parent_id);
        }
        return $this->fetch('', [
            'serviceCategory' => $serviceCategory,
            'language_id' => $this->currentLanguage['id'],
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
            $data = input('post.');//id ,type ,language_id
            $data['language_id'] = $this->currentLanguage['id'];
            //得到它的parent_id
            $map = [
                'parent_id' => $data['map']
            ];
            self::order($data, $map);
        } else {
            return show(0, '置顶失败，未知错误', 'error', 'error', '', '');
        }
    }
//    /**
//     * 删除功能开发
//     * @param Request $request
//     * @return array
//     */
//    public function del(Request $request)
//    {
//        $id = $request::instance()->param();
//        $res = \app\common\model\ServiceCategory::destroy($id);
//        try {
//            if ($res) {
//                return show(1, '', '删除成功');
//            } else {
//                return show(0, '', '删除失败');
//            }
//        } catch (\Exception $e) {
//            return show(0, '', $e->getMessage());
//        }
//    }
}