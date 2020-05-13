<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:37
 * 营销管理单页面管理
 */

namespace app\wavlink\controller;

use think\Facade\Request;
use app\common\model\Language as LanguageModel;
use app\common\model\Marketing as MarketingModel;
use app\wavlink\validate\Marketing as MarketingValidate;

Class Marketing extends BaseAdmin
{
    public function index()
    {
        $result = (new MarketingModel())->getDataByOrder('', '', $this->currentLanguage['id']);
        return $this->fetch('', [
            'marketing' => $result['data'],
            'count' => $result['count'],
        ]);
    }

    public function add()
    {
        $languages = LanguageModel::all([
            'status' => 1,
        ]);
        return $this->fetch('', [
            'languages' => $languages,
        ]);
    }

    public function save(Request $request)
    {
        if (!request()->isPost()) {
            return show(0, 'error', '提交不合法');
        }
        $data = $request::instance()->post();
        $validate = new MarketingValidate();
        if(isset($data['id']) and !empty($data['id'])){
            if($validate->scene('edit')->check($data)){
                try{
                    return $this->update($data);
                }catch (\Exception $exception){
                    return show(0, '', '', '', '', $exception->getMessage());
                }
            }
        }else{
            try{
                if($validate->scene('add')->check($data)){
                    $res = model("Marketing")->add($data);
                    if ($res) {
                        return show(1, '', '', '', '', '添加成功');
                    } else {
                        return show(0, '', '', '', '', '添加失败');
                    }
                }
            }catch (\Exception $exception){
                return show(0, '', '', '', '', $exception->getMessage());
            }
        }
        return show(0, '', '', '', '', $validate->getError());
    }

    public function edit($id)
    {
        if (intval($id) < 0) {
            return show(0, 'error', 'Id不合法');
        }
        $languages = LanguageModel::all([
            'status' => 1
        ]);
        $marketing = MarketingModel::get($id);
        return $this->fetch('', [
            'languages' => $languages,
            'marketing' => $marketing,
        ]);
    }

    /**
     * 改变状态
     *
     */
    public function byStatus()
    {
        $data = input('get.');
        $check['status'] = number_format($data['status']);
        $validate =new MarketingValidate();
        if ($validate->scene('changeStatus')->check($data)) {
            try {
                if ((new MarketingModel())->allowField(true)->save($data, ['id' => $data['id']])) {
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
     * 失效的部分 删除 操作
     */
    public function del()
    {
        $id = input('get.id');
        $validate=new MarketingValidate();
        if (!$validate->scene('del')->check(['id' => $id])) {
            return show(0, 'error', $validate->getError());
        }
        //删除
        try {
            $res = MarketingModel::destroy($id);
            if ($res) {
                return show(1, 'success', '', '', '', '删除成功');
            } else {
                return show(0, 'failed', '', '', '', '删除失败,位置错误');
            }
        } catch (\Exception $e) {
            return show(0, 'failed', '', '', '', $e->getMessage());
        }
    }
}