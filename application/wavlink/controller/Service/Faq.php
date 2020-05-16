<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:37
 */

namespace app\wavlink\controller\Service;

use app\common\model\Faq as FaqModel;
use app\common\model\ServiceCategory as ServiceCategoryModel;
use app\wavlink\controller\BaseAdmin;
use app\wavlink\validate\Faq as FaqValidate;

/***
 * Class Faq
 * @package app\wavlink\controller
 */
Class Faq extends BaseAdmin
{
    /***
     * @return mixed
     */
    public function index()
    {
        $faq = FaqModel::getDataByStatus(1, $this->currentLanguage['id']);
        $con = request()->controller();
        return $this->fetch('', [
            'faq' => $faq['data'],
            'counts' => $faq['count'],
            'language_id' => $this->currentLanguage['id'],
            'con' => $con
        ]);
    }

    public function faq_recycle()
    {
        $faq = FaqModel::getDataByStatus(-1, $this->currentLanguage['id']);
        return $this->fetch('', [
            'faq' => $faq['data'],
            'counts' => $faq['count']
        ]);
    }

    public function add()
    {
        //获取语言
        $language_id = $this->currentLanguage['id'];
        //获取faq的服务分类
        $categorys = ServiceCategoryModel::getSecondCategory($language_id);
        return $this->fetch('', [
            'categorys' => $categorys,
            'language_id' => $language_id
        ]);
    }

    /**
     * 保存操作
     * @return array
     */
    public function save()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            $validate = new FaqValidate();
            if (!empty($data['id'])) {
                if (!$validate->scene('edit')->check($data)) {
                    return show(0, '', '', '', '', $validate->getError());
                }
                return $this->update($data);
            }else{
                if(!$validate->scene('add')->check($data)){
                    return show(0, '', '', '', '', $validate->getError());
                }
                $res = (new FaqModel())->add($data);
                if ($res) {
                    return show(1, '', '', '', '', '添加成功');
                } else {
                    return show(0, '', '', '', '', '添加失败');
                }
            }
        }
    }

    /**
     * 编辑操作
     * @param int $id
     * @param $language_id
     * @return array|mixed
     */
    public function edit($id = 0)
    {
        $id = $this->MustBePositiveInteger($id);
        $language_id = $this->currentLanguage['id'];
        $faq = FaqModel::get($id);
        //获取faq的服务分类
        $categorys = ServiceCategoryModel::getSecondCategory($language_id);
        return $this->fetch('', [
            'faq' => $faq,
            'categorys' => $categorys,
            'language_id' => $language_id
        ]);
    }

    public function byStatus()
    {
        $data = input('get.');
        $validate=new FaqValidate();
        $model=new FaqModel();
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

}