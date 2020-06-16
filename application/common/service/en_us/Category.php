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
use think\facade\Config;

/**
 * Class Category
 * @package app\common\service\en_us
 */
class Category extends BaseService
{
    public function __construct()
    {
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
            return $this->model->getCategoryID($category, $language);
        } catch (\Exception $exception) {
            return 'cuowu';
        }
    }

    /**
     * @param $categoryID
     */

    public function getProductWithCategoryIds($categoryID)
    {
        try {
            return $this->model->getProductWithCategoryIds($categoryID);
        } catch (Exception $exception) {
            if (Config::get('app_debug')) {
                print_r($exception->getMessage());
            }
        }
    }
}