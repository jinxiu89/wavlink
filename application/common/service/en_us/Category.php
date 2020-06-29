<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/6/16 11:12
 * @User: admin
 * @Current File : Category.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\common\service\en_us;

use app\common\model\Content\Category as model;
use think\Exception;
use think\facade\Cache;
use think\facade\Config;
use think\facade\Log;
use function think\__include_file;

/**
 * Class Category
 * @package app\common\service\en_us
 */
class Category extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new model();
    }

    /**
     * @param $category
     * @param $language
     * @return string
     */
    public function getCategoryIds($category, $language)
    {
        try {
            if (false == $this->debug) {
                $data = Cache::get($language . $category . __FUNCTION__);
                if ($data) return $data;
                $obj = $this->model->getCategoryID($category, $language);
                Cache::set($language . $category . __FUNCTION__, $obj);
                return $obj;
            }
            return $this->model->getCategoryID($category, $language);
        } catch (\Exception $exception) {
            if ($this->debug == true) Log::error(__FUNCTION__ . ':' . $exception->getMessage());
            return [];
        }
    }

    /**
     * @param $categoryID
     * @param $category
     * @param $language_id
     * @return array|mixed|\PDOStatement|string|\think\Collection|\think\model\Collection
     */

    public function getProductWithCategoryIds($categoryID, $category, $language_id)
    {
        try {
            if (false == $this->debug) {
                $data = Cache::get(__FUNCTION__ . $language_id . $category);
                if ($data) return $data;
                $obj = $this->model->getProductWithCategoryIds($categoryID);
                Cache::set(__FUNCTION__ . $language_id . $category, $obj);
                return $obj;
            }
            return $this->model->getProductWithCategoryIds($categoryID);
        } catch (Exception $exception) {
            if ($this->debug == true) Log::error(__FUNCTION__ . ':' . $exception->getMessage());
            return $exception->getMessage();
        }
    }
}