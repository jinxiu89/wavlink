<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */

namespace app\common\model\System;

use app\common\model\BaseModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

Class AuthGroup extends BaseModel
{
    protected $table = "auth_group";

//    //用户和组 中间关联，第一个 关联模型，第二个参数 中间关联模型 ，第三个 关联表对应的外键，第四个 当前表对应的外键
    public function manger()
    {
        return $this->belongsToMany('Manger', 'auth_group_access', 'uid', 'group_id');
    }

    /**
     * @param $language_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAuthGroup()
    {
        try {
            $result = $this->paginate();
            $count = $this->count();
            return ['data' => $result, 'count' => $count];
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getAuthById($id)
    {
        $auth = self::relation('AuthRule')->find($id);
        return $auth;
    }

    /**
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAllAuthGroup()
    {
        $data = [
            'status' => 1
        ];
        return $this->where($data)->select();
    }

    /**
     * @param $arr
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getGroupTitle($arr)
    {
        return $this->where('id', 'in', $arr)->where('status', 1)->field('title')->select();
    }
}