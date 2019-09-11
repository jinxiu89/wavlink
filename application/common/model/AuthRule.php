<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */
namespace app\common\model;

use app\common\helper\Category;

Class AuthRule extends BaseModel
{
    protected $table = "auth_rule";

    public function AuthGroup(){
        return $this->belongsToMany('AuthGroup','\app\common\model\GroupRuleAccess','group_id','rule_id');
    }
//    //获取所有权限
//    public static function getAuthRule(){
//        $auth = self::with(['AuthGroup'])
//            ->where('status',1)
//            ->select();
//        return $auth;
//    }
    //获取所有权限
    public function getAll(){
        $cate = AuthRule::all();
        $result = Category::toLevel($cate,'--',0);
        return $result;
    }
    //获取最高级权限
    public function getAuthRuleByParentId($parentId){
        $data = [
            'parent_id' => $parentId,
            'status'    => 1
        ];
        return $this->where($data)->field('id,title')->select();
    }
    public function add($data){
        $authRule = $this->where('id',$data['parent_id'])->field('level')->find();
        if ($authRule){
            $data['level']= $authRule['level']+1;
        }else{
            $data['level'] = 0;
        }
        return $this->save($data);
    }
    //level判断操作
    public function AuthRuleLevel($data){
        $authRule = $this->where('id',$data['parent_id'])->field('level')->find();
        if ($authRule){
            $data['level']= $authRule['level']+1;
        }else{
            $data['level'] = 0;
        }
        return $data;
    }


    //无限极分类显示

    public function authRuleTree(){
        $authRuleRes=$this->order('listorder desc')->select();
        return $this->sort($authRuleRes);
    }

    public function sort($data,$pid=0){
        static $arr=array();
        foreach ($data as $k => $v) {
            if($v['parent_id']==$pid){
                $v['dataid']=$this->getparentid($v['id']);
                $arr[]=$v;
                $this->sort($data,$v['id']);
            }
        }
        return $arr;
    }


    public function getchilrenid($authRuleId){
        $AuthRuleRes=$this->select();
        return $this->_getchilrenid($AuthRuleRes,$authRuleId);
    }

    public function _getchilrenid($AuthRuleRes,$authRuleId){
        static $arr=array();
        foreach ($AuthRuleRes as $k => $v) {
            if($v['parent_id'] == $authRuleId){
                $arr[]=$v['id'];
                $this->_getchilrenid($AuthRuleRes,$v['id']);
            }
        }

        return $arr;
    }


    public function getparentid($authRuleId){
        $AuthRuleRes=$this->select();
        return $this->_getparentid($AuthRuleRes,$authRuleId,True);
    }

    public function _getparentid($AuthRuleRes,$authRuleId,$clear=False){
        static $arr=array();
        if($clear){
            $arr=array();
        }
        foreach ($AuthRuleRes as $k => $v) {
            if($v['id'] == $authRuleId){
                $arr[]=$v['id'];
                $this->_getparentid($AuthRuleRes,$v['parent_id'],False);
            }
        }
        asort($arr);
        $arrStr=implode('-', $arr);
        return $arrStr;
    }

}