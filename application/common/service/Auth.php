<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/9/11
 * Time: 15:10
 */

namespace app\common\service;


use think\Model;

class Auth extends Model
{
//    protected $mangerModel;
//    protected $roleModel;
//    protected $permissionModel;

    /**
     * Auth constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        parent::__construct($data);

    }

    /***
     * @param $handler
     * @param $notCheck
     * @param $uid
     */
    public function checkPermission($handler,$notCheck,$uid){
        return;
    }
}