<?php

/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */

namespace app\common\model\System;

use app\common\model\BaseModel;
use think\Collection;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\exception\PDOException;
use think\model\relation\BelongsToMany;

/**
 * Class Manger
 * @package app\common\model
 */
class Manger extends BaseModel
{
    protected $table = 'manger'; //使用user表

    /**
     * @return BelongsToMany
     */
    public function AuthGroup()
    {
        return $this->belongsToMany('AuthGroup', '\app\common\model\System\AuthGroupAccess', 'group_id', 'uid');
    }

    /**
     * @param $id
     * @return array|PDOStatement|string|Collection
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function getAuthGroup($id)
    {
        return self::with(['AuthGroup'])->select($id);
    }

    public function updateById($data, $id)
    {
        //allowField 过滤data数组中非数据表中的数据
        return $this->allowField(true)->save($data, ['id' => $id]);
    }

    public function getUsernameByAdd($username)
    {
        $data = [
            'username' => $username
        ];
        return $this->where($data)->select();
    }


    //关联模型添加管理员数据操作

    /**
     * @param $data
     * @return bool
     * @throws PDOException
     */
    public function SaveManger($data)
    {
        $this->startTrans();
        try {
            $this->save($data['data']);
            $mangers = $this::get($this->id);
            $res = $mangers->AuthGroup()->saveAll([$data['rules']]);
            $this->commit();
        } catch (\Exception $exception) {
            $this->rollback();
            return false;
        }
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    //关联模型更新数据操作

    /**
     * @param $data
     * @return bool
     * @throws PDOException
     */
    public function saveEditManger($data)
    {
        $this->startTrans();
        try {
            $this::update($data['data'], ['id' => $data['data']['id']]);
            //从关联模型查询数据
            $groups = Db::table('auth_group_access')->where(['uid' => $data['data']['id']])->select();
            $group = [];
            foreach ($groups as $k => $v) {
                $group[] = $v['group_id'];
            }
            $mangerGroup = $this->get($data['data']['id']);
            if (!empty($data['rules'])) {
                //先删除中间表数据,然后再新增
                $mangerGroup->AuthGroup()->detach($group); //删除
                $res = $mangerGroup->AuthGroup()->attach($data['rules']); //新增
            } else {
                //如果更新的数据中没有rules ，就去关联模型中删掉它的记录。
                $res = $mangerGroup->AuthGroup()->detach($group);
            }
            $this->commit();
        } catch (\Exception $exception) {
            $this->rollback();
            return false;
        }
        if ($res !== true) {
            return true;
        } else {
            $this->rollback();
            return false;
        }
    }

    //修改密码操作。
    public function editPassword($password)
    {
        $password['code'] = mt_rand(100, 1000);
        $data = [
            'password' => md5($password['password'] . $password['code']),
            'code' => $password['code'],
        ];
        $res = $this::update($data, ['id' => $password['id']]);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }
}