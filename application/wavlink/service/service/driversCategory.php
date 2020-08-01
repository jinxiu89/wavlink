<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/5/20 15:59
 * @User: admin
 * @Current File : driversCategory.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\wavlink\service\service;

use app\common\model\Service\DriversCategory as model;
use app\wavlink\service\Base;
use think\facade\Cache;

/**
 * Class driversCategory
 * @package app\wavlink\service\service
 */
class driversCategory extends Base
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new model();
    }

    /**
     * @param $parent_id
     */
    public function getParent($parent_id)
    {
        try {
            return $this->model->field('id,parent_id,level,path')->where(['id' => $parent_id])->find();
        } catch (\Exception $exception) {
            //todo:: 异常
        }
    }

    /**
     * @param $category
     * @param $language
     * @return array|string
     */
    public function getCategoryID($category, $language)
    {
        try {
            if (false == $this->debug) {
                $data = Cache::get(__FUNCTION__ . $language . $category);
                if ($data) return $data;
                $obj = $this->model->getCategoryID($category, $language);
                Cache::set(__FUNCTION__ . $language . $category, $obj);
            }
            return $this->model->getCategoryID($category, $language);
        } catch (\Exception $exception) {
            return [];
        }
    }

    /**
     * @param string $status
     * @param $language_id
     * @return array
     */
    public function getDataByLanguageId($status, $language_id)
    {
        try {
            $response = $this->model->getDataByLanguageId($status, $language_id);
            $result['count'] = $response->count();
            $result['data'] = $response->paginate(25);
            return $result;
        } catch (\Exception $exception) {
            return $exception->getMessage();
            return [];
        }
    }

    /**
     * @param $status
     * @param $language_id
     */
    public function getCategoryByLanguage($status,$language_id){
        try {
            $response = $this->model->getDataByLanguageId($status, $language_id);
            return $response->select();
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}