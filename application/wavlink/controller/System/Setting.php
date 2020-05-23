<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:37
 */

namespace app\wavlink\controller\System;

use app\common\model\Language as LanguageModel;
use app\common\model\System\Setting as SettingModel;
use app\wavlink\controller\BaseAdmin;
use app\wavlink\validate\Setting as SettingValidate;

/**
 * Class Setting
 * @package app\wavlink\controller
 */
Class Setting extends BaseAdmin
{
    public function index()
    {
        $data = SettingModel::get(['language_id' => $this->currentLanguage['id']]);
        return $this->fetch('', [
            'language_id'=>$this->currentLanguage['id'],
            'data' => $data,
        ]);
    }

    public function add()
    {
        $languages = LanguageModel::all([
            'status' => 1
        ]);
        return $this->fetch('', [
            'languages' => $languages,
        ]);
    }

    /**
     * @return array|void
     */
    public function save()
    {
        if (!request()->isPost()) {
            return show(0, '', '非法操作');
        }
        $data = input('post.');
        $validate=new SettingValidate();
        if($validate->scene('add')->check($data)){
            try{
                return $this->update($data);
            }catch (\Exception $exception){
                return show(0, '', '', '', '', $exception->getMessage());
            }
        }
        return show(0, '', '', '', '',$validate->getError());
    }

    public function edit($id)
    {
        if (intval($id) < 0) {
            return show(0, '', 'id不合法');
        }
        $setting = SettingModel::get($id);
        $languages = LanguageModel::all([
            'status' => 1
        ]);
        return $this->fetch('', [
            'setting' => $setting,
            'languages' => $languages
        ]);
    }

}