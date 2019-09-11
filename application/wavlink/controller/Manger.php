<?php
/**
 * Created by PhpStorm.
 * User: guo
 * 系统管理之管理员设定
 * Date: 2017/8/23
 * Time: 10:37
 */
namespace app\wavlink\controller;

use think\Request;
use app\common\model\Manger as MangerModel;
use app\common\model\AuthGroup as AuthGroupModel;
use app\common\model\Language as LanguageModel;
Class Manger extends BaseAdmin
{

    public function index()
    {
        $auth = new Auth();
        $mangers = MangerModel::getDataByStatus(1);
        foreach ($mangers['data'] as $k => $v){
            $_groupTitle = $auth->getGroups($v['id']);
            $titles = array_column($_groupTitle,'title'); //获取title 这一列的值
            $groupTile = implode(',',$titles); //数组组合成一个字符串，用逗号分开
            $v['groupTitle'] = $groupTile;
        }
        return $this->fetch('', [
            'mangers' => $mangers['data'],
            'counts' => $mangers['count']
        ]);
    }

    /**
     * @return mixed
     * 已停用管理员列表
     */
    public function manger_stop()
    {
        $mangers = MangerModel::getDataByStatus(-1);
        return $this->fetch('', [
            'mangers' => $mangers['data'],
            'counts' => $mangers['count']
        ]);
    }

    //添加管理员
    public function add()
    {
        $authGroup = AuthGroupModel::all([
            'status' =>1
        ]);
        $languages = LanguageModel::all([
           'status' => 1
        ]);
        return $this->fetch('',[
            'authGroup' => $authGroup,
            'languages' => $languages
        ]);
    }


    //编辑用户
    public function edit($id = 0)
    {
        if (intval($id) < 1) {
            $this->error('参数不合法');
        }
        $user = MangerModel::get($id);
        $authGroup = AuthGroupModel::all([
            'status'=>1
        ]);
        $group = model("AuthGroupAccess")->where(array('uid'=>$id))->select();
        $languages = LanguageModel::all([
           'status' =>1
        ]);
        $data=array();
        foreach ($group as $k => $v){
            $data[]=$v['group_id'];
        }
        return $this->fetch('', [
            'user'      => $user,
            'authGroup' => $authGroup,
            'groups'    =>$data,
            'languages' => $languages
//            'groupId'     => $group['group_id'],
        ]);
    }
    public function saveEdit(){
        $data = input('post.');
        $validate = Validate('Manger');
        if (!$validate->scene('edit')->check($data)){
            return show(0,'','','','',$validate->getError());
        }
        $manger = (new MangerModel())->saveEditManger($data);
        if ($manger) {
            return show(1,'','','','', '更新成功');
        } else {
            return show(1,'','','','', '更新失败');
        }
    }
    public function stop(Request $request)
    {
        $ids = $request::instance()->param();
        $res = $ids['username'] == 'admin';
        try {
            if (!$res) {
                $result = model("Manger")->where('id', $ids['id'])->update(['status' => $ids['status']]);
                if ($result) {
                    return show(1, '','','','', '停用成功');
                } else {
                    return show(0, '', '','','','停用失败');
                }
            } else {
                return show(0, '','','','', '不能停用总管理员');
            }
        }catch (\Exception $e){
            return show(0,'',$e->getMessage());
        }
    }

    //关联模型操作
    //保存
    public function addManger(){
        $data = input('post.');
        $validate = Validate('Manger');
        if (!$validate->check($data)){
            return show(0,'','','','',$validate->getError());
        }
        if ($data['password'] != $data['password2']) {
            return show(0,'','两次输入的密码不一致');
        }
        $res =(new MangerModel())->SaveManger($data);
        if ($res){
            return show(1,'','','','','添加成功');
        }else{
            return show(0,'','','','','添加失败哦,是不是有什么没有加哦');
        }
    }
    //修改密码
    public function password($id){
        if (request()->isPost()){
            $data = input('post.');
            if ($data['password'] !== $data['password2']){
                return show(0,'','两次输入的密码不一样');
            }
            $password = [
                'id' => $id,
                'password' => $data['password']
            ];
            $res = (new MangerModel())->editPassword($password);
            if ($res){
                return show(1,'','修改密码成功');
            }else{
                return show(0,'','修改密码失败');
            }
        }
        if (intval($id) < 0){
            return show(0,'','ID不合法');
        }
        return $this->fetch('',[
            'id' => $id
        ]);
    }
}