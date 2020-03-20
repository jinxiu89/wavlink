<?php
/**
 * Created by PhpStorm.
 * User: guo
 * 系统管理之管理员设定
 * Date: 2017/8/23
 * Time: 10:37
 */

namespace app\wavlink\controller;

use think\Db;
use think\Facade\Request;
use app\common\model\Manger as MangerModel;
use app\common\model\AuthGroup as AuthGroupModel;
use app\common\model\Language as LanguageModel;
use app\wavlink\validate\Manger as MangerValidate;
use think\facade\Validate;

/***
 * Class Manger
 * @package app\wavlink\controller
 */
Class Manger extends BaseAdmin
{

    public function index()
    {
        $auth = new Auth();
        $mangers = MangerModel::getDataByStatus(1);
        foreach ($mangers['data'] as $k => $v) {
            $_groupTitle = $auth->getGroups($v['id']);
            $titles = array_column($_groupTitle, 'title'); //获取title 这一列的值
            $groupTile = implode(',', $titles); //数组组合成一个字符串，用逗号分开
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
            'status' => 1
        ]);
        $languages = LanguageModel::all([
            'status' => 1
        ]);
        return $this->fetch('', [
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
        $authGroup = AuthGroupModel::all(['status' => 1]);
        $group = Db::table('auth_group_access')->where(['uid'=>$id])->select();
        $languages = LanguageModel::all([
            'status' => 1
        ]);
        $data = array();
        foreach ($group as $k => $v) {
            $data[] = $v['group_id'];
        }
        return $this->fetch('', [
            'user' => $user,
            'authGroup' => $authGroup,
            'groups' => $data,
            'languages' => $languages
//            'groupId'     => $group['group_id'],
        ]);
    }

    public function saveEdit()
    {
        $temp = input('post.',[],'htmlspecialchars');
        $validate = new MangerValidate();
        if (!$validate->scene('edit')->check($temp)) {
            return show(0, '', '', '', '', $validate->getError());
        }
        $temp['language_id']=implode(',',$temp['language']);
        unset($temp['language']);
        $rules=$temp['rules'];
        unset($temp['rules']);
        $data['data']=$temp;
        $data['rules']=$rules;
        $manger = (new MangerModel())->saveEditManger($data);
        if ($manger) {
            return show(1, '', '', '', '', '更新成功');
        } else {
            return show(1, '', '', '', '', '更新失败');
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
                    return show(1, '', '', '', '', '停用成功');
                } else {
                    return show(0, '', '', '', '', '停用失败');
                }
            } else {
                return show(0, '', '', '', '', '不能停用总管理员');
            }
        } catch (\Exception $e) {
            return show(0, '', $e->getMessage());
        }
    }

    //关联模型操作
    //保存
    /**
     * addManger
     * 2020.0320 kevin qiu 修正bug：验证不严谨导致的数据问题
     *
     */
    public function addManger()
    {
        $temp = input('post.',[],'htmlspecialchars');
        $validate = Validate('Manger');
        if (!$validate->scene('add')->check($temp)) {
            return show(0, '', '', '', '', $validate->getError());
        }
        if ($temp['password'] != $temp['password2']) {
            return show(0, '', '两次输入的密码不一致');
        }
        $temp['code'] = mt_rand(100, 1000);
        $temp['password']=md5($temp['password'].$temp['code']);
        $temp['language_id']=implode(',',$temp['language']);
        $rules=$temp['rules'];
        unset($temp['password2']);
        unset($temp['rules']);
        unset($temp['language']);
        $data['data']=$temp;
        $data['rules']=$rules;
        $res = (new MangerModel())->SaveManger($data);
        if ($res) {
            return show(1, '', '', '', '', '添加成功');
        } else {
            return show(0, '', '', '', '', '添加失败哦,是不是有什么没有加哦');
        }
    }

    //修改密码

    /**
     * @param $id
     * @return mixed|void
     */
    public function password($id)
    {
        if($this->request->isGet()){
            if (intval($id) < 0) {
                return show(0, '', 'ID不合法','','','ID不合法');
            }
            return $this->fetch('', [
                'id' => $id
            ]);
        }
        if ($this->request->isPost()) {
            $data = input('post.',[],'htmlspecialchars');;
            $validate=new MangerValidate();
            if(!$validate->scene('changePassword')->check($data)){
                return show(0, '', '', '', '', $validate->getError());
            }
            if ($data['password'] !== $data['password2']) {
                return show(0, '', '两次输入的密码不一样','','','两次输入的密码不一样');
            }
            $res = (new MangerModel())->editPassword($data);
            if ($res) {
                return show(1, '', '修改密码成功','','','修改密码成功');
            } else {
                return show(0, '', '修改密码失败','','','修改密码失败');
            }
        }
    }

    public function byStatus()
    {
        $data = input('get.');
        $validate = new MangerValidate();
        $model = new MangerModel();
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