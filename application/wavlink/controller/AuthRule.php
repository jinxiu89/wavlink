<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/23
 * Time: 10:37
 */
namespace app\wavlink\controller;
use \app\common\model\AuthRule as AuthRuleModel;
Class AuthRule extends BaseAdmin
{
    public $obj;
    protected $beforeActionList = [
        'first',
    ];
    public function first(){
        $this->obj=model("AuthRule");
    }

    public function index(){
        $result = $this->obj->authRuleTree();
        return $this->fetch('',[
            'authRule' => $result,

        ]);
    }
    public function add(){
        $authRules = $this->obj->getAll();
        return $this->fetch('',[
            'authRules' => $authRules
        ]);
    }
    public function save(){
        $data = input('post.');
        if (!empty($data['id'])){
            if ($data['id'] == $data['parent_id']){
                return show(0,'','不能分在自己名下');
            }

            $authRuleData = $this->obj->AuthRuleLevel($data);

            return $this->update($authRuleData);
        }
        $result = (new AuthRuleModel())->save($data);
        if ($result){
            return show(1, '', '', '', '', '添加成功');
        }else{
            return show(0, '', '', '', '', '添加失败');
        }
    }
    public function edit($id){
        if (intval($id) < 0){
            return show(0,'','ID不合法');
        }
        $authRule = AuthRuleModel::get($id);
        $authRules = $this->obj->getAll();
        return $this->fetch('',[
            'authRule' => $authRule,
            'authRules' => $authRules
        ]);
    }

    //状态操作
    public function AuthStatus(){
        $data = input('get.');
        //判断是否有子级权限
        $childChildRes =$this->obj->getchilrenid($data['id']);
        if ($childChildRes){
            return show(0,'','有子权限，请先修改子权限');
        }
        //权限禁用,status=0
        if($data['status'] == 0){
            $res =  $this->status($data);
            if ($res){
                return show(1,'','禁用成功');
            }else{
                return show(0,'','禁用失败');
            }
        }
        //权限启用,status = 1
        if ($data['status'] == 1){
            $res  = $this->status($data);
            if ($res ){
                return show(1,'','恢复成功');
            }else{
                return show(0,'','恢复失败');
            }
        }
    }
}