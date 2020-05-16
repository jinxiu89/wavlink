<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */
namespace app\common\model;



use think\model\Pivot;

/**
 * Class AuthGroupAccess
 * @package app\common\model
 */
Class AuthGroupAccess extends Pivot
{
    protected $autoWriteTimestamp = false;
    protected $table = 'auth_group_access';
}