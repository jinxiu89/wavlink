<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:37
 */

namespace app\wavlink\controller;

use think\facade\Request;
use app\common\model\About as AboutModel;
use app\wavlink\validate\About as AboutValidate;

/***
 * Class About
 * @package app\wavlink\controller
 * 关于我们：有语言区分
 */
Class About extends BaseAdmin
{
    /***
     * @return mixed
     * 20190916
     * 修改语言获取方式为session方式
     */
    public function index()
    {
        $order = [
            'status' => 'desc',
            'listorder' => 'desc'
        ];
        try{
            $about = AboutModel::getAbouts($this->currentLanguage['id']);
            return $this->fetch('', [
                'about' => $about['data'],
                'count' => $about['count'],
                'language_id' => $this->currentLanguage['id']
            ]);
        }catch (\Exception $exception){
            $this->error($exception->getMessage());
        }
    }

    /**
     * @return mixed
     */
    public function add()
    {
        return $this->fetch('', [
            'language_id' => $this->currentLanguage['id']
        ]);
    }

    /**
     * @param Request $request
     * @return array|void
     */
    public function save(Request $request)
    {
        if (request()->isAjax()) {
            $data = $request::instance()->post();
            $validate = new AboutValidate();
            if (isset($data['id']) and !empty($data['id'])) {
                if ($validate->scene('edit')->check($data)) {
                    try {
                        return $this->update($data);
                    } catch (\Exception $exception) {
                        return show(0, '', '', '', '', $exception->getMessage());
                    }
                }
            } else {
                if ($validate->scene('add')->check($data)) {
                    try {
                        $res = (new AboutModel())->add($data);
                        if ($res) {
                            return show(1, '', '', '', '', '添加成功');
                        } else {
                            return show(1, '', '', '', '', '添加失败');
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
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        $id = $this->MustBePositiveInteger($id);
        $about = AboutModel::get($id);
        return $this->fetch('', [
            'about' => $about,
            'language_id' => $this->currentLanguage['id']
        ]);
    }
    /**
     * 改变状态，当该分类存在产品或者有子分类时不能
     */
    public function byStatus()
    {
        $data = input('get.');
        $validate=new AboutValidate();
        $model=new AboutModel();
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
}