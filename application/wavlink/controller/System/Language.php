<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:37
 */

namespace app\wavlink\controller\System;

use app\wavlink\controller\BaseAdmin;
use \think\Facade\Request;
use app\common\model\Language as LanguageModel;
use app\wavlink\validate\Language as LanguageValidate;

/**
 * Class Language
 * @package app\wavlink\controller\System
 * 功能归类优化
 */
Class Language extends BaseAdmin
{
    /**
     * @return mixed
     * 系统管理之语言管理
     * 状态值为1的语言列表
     */
    public function index()
    {
        try {
            $languages = LanguageModel::getDataByLanguage(['status' => 1], ['id' => 'desc', 'create_time' => 'desc']);
            return $this->fetch('', [
                'languages' => $languages['data'],
                'counts' => $languages['count'],
            ]);

        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }

    /**
     * @return mixed
     * 已经停用的语言列表
     * @$status=0
     */
    public function language_stop()
    {
        try {
            $languages = LanguageModel::getDataByLanguage(['status' => -1], ['id' => 'desc', 'create_time' => 'desc']);
            return $this->fetch('', [
                'languages' => $languages['data'],
                'counts' => $languages['count']
            ]);
        }catch (\Exception $exception){
            $this->error($exception->getMessage());
        }
    }
    //添加语言

    /**
     * @return mixed
     */
    public function add()
    {
        return $this->fetch();
    }

    /**
     * @param Request $request
     * @return array|void
     */
    public function save(Request $request)
    {
        /*
         * 做一下严格判定
         *
         * */
        if (!request()->isPost()) {
            $this->error('请求失败');
        }
        $data = $request::instance()->post();
        $validate = new LanguageValidate();
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
                    $res = LanguageModel::create($data);
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

    //编辑语言页面

    /**
     * @param int $id
     * @return mixed
     */
    public function edit($id = 0)
    {
        if (intval($id) < 1) {
            $this->error('参数不合法');
        }
        $language = LanguageModel::get($id);
        return $this->fetch('', [
            'language' => $language,
        ]);
    }

    /**
     * 改变状态，当该分类存在产品或者有子分类时不能
     */
    public function byStatus()
    {
        $data = input('get.');
        $validate=new LanguageValidate();
        $model=new LanguageModel();
        if ($validate->scene('changeStatus')->check($data)) {
            try {
                $result=$model->checkData($data['id']);
                if ($result) {
                    return show(0, "failed", '', '', '', $result);
                }
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