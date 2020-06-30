<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/6/28 16:47
 * @User: admin
 * @Current File : Drivers.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\common\service\en_us;

use app\common\model\Service\Drivers as model;
use app\wavlink\service\service\driversCategory as Category;
use app\common\model\Service\ServiceCategory;
use think\facade\Cache;
use think\facade\Log;
use think\Paginator;

/**
 * Class Drivers
 * @package app\common\service\en_us
 */
class Drivers extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new model();
    }

    /**
     * @param $language_id
     * @param $category
     */
    public function getTopCategory($language_id, $category)
    {
        try {
            if ($this->debug == false) {
                $data = Cache::get(__FUNCTION__ . $language_id . $category);
                if ($data) return $data;
                $obj = ServiceCategory::getTopCategory($language_id, $category);
                Cache::set(__FUNCTION__ . $language_id . $category, $obj);
                return $obj;
            }
            return ServiceCategory::getTopCategory($language_id, $category);
        } catch (\Exception $exception) {
            if ($this->debug == true) Log::error(__FUNCTION__ . ':' . $exception->getMessage());
            return [];
        }
    }

    /***
     * @param $language_id
     * @param $order
     * @return array|mixed|Paginator
     */
    public function getDriversByLanguage($language_id, $order)
    {
        try {
            if ($this->debug == false) {
                $data = Cache::get(__FUNCTION__ . $language_id);
                if ($data) return $data;
                $obj = $this->model->getDriversByLanguage($language_id, $order);
                Cache::set(__FUNCTION__ . $language_id, $obj);
                return $obj;
            }
        } catch (\Exception $exception) {
            if ($this->debug == true) Log::error(__FUNCTION__ . ":" . $exception->getMessage());
            return $exception->getMessage();
        }
    }

    /**
     * @param $category
     * @param $language_id
     */
    public function getCategoryID($category, $language_id)
    {
        try {
            if ($this->debug == false) {
                $data = Cache::get(__FUNCTION__ . $language_id . $category);
                if ($data) return $data;
                $obj = (new Category())->getCategoryID($category, $language_id);
                Cache::set(__FUNCTION__ . $language_id . $category, $obj);
                return $obj;
            }
        } catch (\Exception $exception) {
            if ($this->debug == true) Log::error(__FUNCTION__ . ":" . $exception->getMessage());
        }
    }

    /**
     * @param $language_id
     * @param $categoryID
     * @param $order
     */
    public function getDriversByCategoryIds($language_id, $categoryID, $order)
    {
        try {
            if ($this->debug == false) {
                $data = Cache::get(__FUNCTION__ . $language_id . $order);
                if ($data) return $data;
                $obj = $this->model->getDriversByCategoryIds($language_id, $categoryID, $order);
                Cache::set(__FUNCTION__ . $language_id . $order, $obj);
                return $obj;
            }
        } catch (\Exception $exception) {
            if ($this->debug == true) Log::error(__FUNCTION__ . ":" . $exception->getMessage());
        }
    }
}