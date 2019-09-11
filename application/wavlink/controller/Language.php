<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:37
 */
namespace app\wavlink\controller;
use \think\Request;
use app\common\model\Language as LanguageModel;
Class Language extends BaseAdmin
{
    /**
     * @return mixed
     * 系统管理之语言管理
     * 状态值为1的语言列表
     */
    public function index()
    {
        $languages = LanguageModel::getDataByOrder(['status'=>1]);
        return $this->fetch('', [
            'languages' => $languages['data'],
            'counts' => $languages['count'],
        ]);
    }
    /**
     * @return mixed
     * 已经停用的语言列表
     * @$status=0
     */
    public function language_stop(){
        $languages = LanguageModel::getDataByOrder(['status'=>-1]);
        return $this->fetch('',[
            'languages'=>$languages['data'],
            'counts' => $languages['count']
        ]);
    }
    //添加语言
    public function add()
    {
        return $this->fetch();
    }
    public function save(Request $request)
    {
        /*
         * 做一下严格判定
         * */
        if (!request()->isPost()) {
            $this->error('请求失败');
        }
        $data = $request::instance()->post();
        $validate = validate('Language');
        if (!$validate->check($data)) {
            return show(0,'error',$validate->getError());
        }
        if (!empty($data['id'])) {
            return $this->Update($data);
        }
        //把$data 提交给model层
        $res = LanguageModel::create($data);
        if ($res) {
            return show(1,'','','','', '添加成功');
        } else {
            return show(1,'','','','', '添加失败');
        }
    }
    //编辑语言页面
    public function edit($id = 0)
    {
        if (intval($id) < 1) {
            $this->error('参数不合法');
        }
        $language =LanguageModel::get($id);
//        $languages = LanguageModel::getLanguage(1);
        return $this->fetch('', [
//            'languages' => $languages,
            'language' => $language,
        ]);
    }
}