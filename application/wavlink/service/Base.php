<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/5/5 9:50
 * @User: admin
 * @Current File : Base.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\wavlink\service;


use think\facade\Config;

/**
 * Class Base
 * @package app\wavlink\service
 */
class Base
{
    protected $model;
    protected $bucket;
    protected $debug;

    public function __construct()
    {
        //初始化各种玩意（在base里不用写比较细的初始化，细化的在具体的类里处理）
        $this->debug=Config::get('app_debug');
    }

    /**
     * @param $data
     * @return string
     */
    public function create($data)
    {
        try {
            return $this->model->create($data); //返回的是一个当前模型的实例
        } catch (\Exception $exception) {
            return $exception->getMessage();//todo:: 异常
        }
    }

    /**
     * @param $data
     * @return string
     */
    public function update($data)
    {
        try {
            return $this->model->saveData($data); //返回的是一个当前模型的实例
        } catch (\Exception $exception) {
            return $exception->getMessage();//todo:: 异常
        }
    }

    /**
     * @param $id
     * @return string
     */
    public function getDataById($id)
    {
        try {
            return $this->model->get($id);
        } catch (\Exception $exception) {
            return $exception->getMessage();//异常管理后面优化
        }
    }


}