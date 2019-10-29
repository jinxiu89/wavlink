<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:37
 */

namespace app\wavlink\controller;

use think\App;
use think\Exception;
use think\facade\Request;
use app\common\model\AuthGroup as AuthGroupModel;
use app\wavlink\validate\AuthGroup as AuthGroupValidate;
/***
 * Class AuthGroup
 * @package app\wavlink\controller
 *
 */
class AuthGroup extends BaseAdmin
{
    protected $obj;

    /**
     * AuthGroup constructor.
     * @param App|null $app
     */
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->obj=new AuthGroupModel();
    }

    /**
     * @return mixed
     */
    public function index()
    {
        try{
            $result = $this->obj->getAuthGroup();
            return $this->fetch('', [
                'authGroup' => $result['data'],
                'count' => $result['count'],
            ]);
        }catch (\Exception $exception){
            $this->error($exception->getMessage());
        }
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
        $rules = explode(',', $authGroup['rules']);
        return $this->fetch('', [
            'authGroup' => $authGroup,
            'authRule' => $authRule,
            'ParentAuthRule' => $ParentAuthRule,
            'rule' => $rules,
        ]);
    }

    /***
     *
     */
    public function byStatus()
    {
        $data = input('get.');
        $validate=new AuthGroupValidate();
        $model=new AuthGroupModel();
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