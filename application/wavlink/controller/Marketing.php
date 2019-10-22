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
        $validate = Validate('Marketing');
        if (!$validate->check($data)) {
            return show(0, 'error', $validate->getError());
        }
        if (!empty($data['id'])) {
            return $this->update($data);
        }
        $res = model("Marketing")->add($data);
        if ($res) {
            return show(1, '', '', '', '', '添加成功');
        } else {
            return show(0, '', '', '', '', '添加失败');
        }
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
}