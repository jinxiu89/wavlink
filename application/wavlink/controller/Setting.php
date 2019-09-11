<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:37
 */
namespace app\wavlink\controller;
use app\common\model\Language as LanguageModel;
use app\common\model\Setting as SettingModel;
use app\wavlink\validate\Setting as SettingValidate;
Class Setting extends BaseAdmin
{
    public function index(){
        $setting = (new SettingModel())->getSetting();

        return $this->fetch('',[
            'setting' => $setting,
        ]);
    }
    public function add(){
        $languages = LanguageModel::all([
            'status'=>1
        ]);
        return $this->fetch('',[
            'languages' => $languages,
        ]);
    }
    public function save(){
        if (!request()->isPost()){
            return show(0,'','非法操作');
        }
        $data = input('post.');
        (new SettingValidate())->goCheck();
        if (!empty($data['id'])){
            return $this->update($data);
        }
        $res = SettingModel::create($data);
        if ($res) {
            return show(1,'','','','', '添加成功');
        } else {
            return show(1,'','','','', '添加失败');
        }
    }
    public function edit($id){
        if (intval($id) < 0){
            return show(0,'','id不合法');
        }
        $setting = SettingModel::get($id);
        $languages = LanguageModel::all([
            'status'=>1
        ]);
        return $this->fetch('',[
            'setting'=>$setting,
            'languages'=>$languages
        ]);
    }

}