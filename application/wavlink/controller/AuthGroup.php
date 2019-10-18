<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:37
 */

namespace app\wavlink\controller;

use think\facade\Request;

/***
 * Class AuthGroup
 * @package app\wavlink\controller
 *
 */
class AuthGroup extends BaseAdmin
{
    public $obj;

    public function _initialize()
    {
        parent::_initialize();
        $this->obj = model("AuthGroup");
    }

    public function index()
    {
        $result = $this->obj->getAuthGroup();
        return $this->fetch('', [
            'authGroup' => $result['data'],
            'count' => $result['count'],
        ]);
    }

    public function add()
    {
        //验证权限
        $authRule = model("AuthRule")->authRuleTree();
        //获取父级权限
        $ParentAuthRule = model("AuthRule")->getAuthRuleByParentId(0);
        return $this->fetch('', [
            'authRule' => $authRule,
            'ParentAuthRule' => $ParentAuthRule
        ]);
    }

    /**
     * @return array
     */
    public function save()
    {
        $data = Request::instance()->post();
        if ($data['rules']) {
            $data['rules'] = implode(',', $data['rules']);
        }
        if (!empty($data['id'])) {
            $groupData['title'] = $data['title'];
            $groupData['description'] = $data['description'];
            $groupData['rules'] = $data['rules'];
            $groupData['id'] = $data['id'];
            return $this->update($groupData);
        }
        $res = $this->obj->save($data);
        if ($res) {
            return show(1, '', '', '', '', '添加成功');
        } else {
            return show(0, '', '', '', '', '添加失败');
        }
    }

    /**
     * @param $id
     * @return array|mixed
     */
    public function edit($id)
    {
        if (intval($id) < 0) {
            return show(0, 'error', 'ID不合法');
        }
        $authRule = model("AuthRule")->authRuleTree();
        //获取父级权限
        $ParentAuthRule = model("AuthRule")->getAuthRuleByParentId(0);
        $authGroup = $this->obj->find($id);
//        $rules =model("GroupRuleAccess")->where(array('group_id'=>$id))->select();
//        $data=array();
//        foreach ($rules as $k => $v){
//            $data[]=$v['rule_id'];
//        }
        $rules = explode(',', $authGroup['rules']);
        return $this->fetch('', [
            'authGroup' => $authGroup,
            'authRule' => $authRule,
            'ParentAuthRule' => $ParentAuthRule,
            'rule' => $rules,
        ]);
    }

}