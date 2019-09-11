<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */
namespace app\common\model;

Class Manger extends BaseModel
{
    protected $table = 'manger';//使用user表
    public function AuthGroup(){
        return $this->belongsToMany('AuthGroup','\app\common\model\AuthGroupAccess','group_id','uid');
    }
    public static function getAuthGroup($id){
        return self::with(['AuthGroup'])->select($id);
    }
    public function updateById($data,$id){
        //allowField 过滤data数组中非数据表中的数据
        return $this->allowField(true)->save($data,['id'=>$id]);
    }
    public function getUsernameByAdd($username){
        $data=[
            'username' =>$username
        ];
        return $this->where($data)->select();
    }


    //关联模型添加管理员数据操作
    public function SaveManger($data){
        $data['code'] = mt_rand(100, 1000);
        $language_id = implode(',',$data['language']);
        $mangerData=[
            'username' => $data['username'],
            'name'     => $data['name'],
            'password' => md5($data['password'].$data['code']),
            'mobile'   => $data['mobile'],
            'email'    => $data['email'],
            'code'     => $data['code'],
            'language_id' => $language_id
        ];
        if ($this->save($mangerData) && !empty($data['rules'])){
            $mangers = $this::get($this->id);
            $res =  $mangers->AuthGroup()->saveAll([$data['rules']]);
        }else{
            //如果没有给这个管理员添加用户组，就不添加进去，直接删了。
            $this::destroy($this->id);
            return false;
        }
        if($res){
            return true;
        }else{
            return false;
        }
    }

    //关联模型更新数据操作
    public function saveEditManger($data){
        $language_id = implode(',',$data['language']);
        $userData=[
            'username' => $data['username'],
            'name'     => $data['name'],
            'mobile'   => $data['mobile'],
            'email'    => $data['email'],
            'language_id' => $language_id
        ];
//        更新管理员表数据
        $result = $this::update($userData,['id'=>$data['id']]);

        //从关联模型查询数据
        $groups =model("AuthGroupAccess")->where(array('uid'=>$data['id']))->select();
        $group=array();
        foreach ($groups as $k => $v){
            $group[]=$v['group_id'];
        }
        $mangerGroup =  $this->get($data['id']);
        if ($result && !empty($data['rules'])){
            //先删除中间表数据,然后再新增
            $mangerGroup->AuthGroup()->detach($group); //删除
            $res= $mangerGroup->AuthGroup()->attach($data['rules']); //新增
            return($res !==true) ? true : false;
        }else{
            //如果更新的数据中没有rules ，就去关联模型中删掉它的记录。
            $res = $mangerGroup->AuthGroup()->detach($group);
            return ($res !== true) ? true : false ;
        }
    }

    //修改密码操作。
    public function editPassword($password){
        $password['code'] = mt_rand(100,1000);
        $data = [
            'password' => md5($password['password'].$password['code']),
            'code'     => $password['code'],
        ];
        $res = $this::update($data,['id'=>$password['id']]);
        if ($res){
            return true;
        }else{
            return false;
        }
    }


}