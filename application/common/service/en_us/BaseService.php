<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/4/9 14:46
 * @User: admin
 * @Current File : BaseService.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\common\service\en_us;


use AlibabaCloud\Client\Config\Config;
use app\common\model\Content\Category as ProductCategory;
use think\facade\Cache;
use think\facade\Log;

/**
 * Class BaseService
 * @package app\common\service\en_us
 * 以后的缓存都在service层来做
 */
class BaseService
{
    protected $model;
    protected $debug;

    public function __construct()
    {
        $this->debug = Config::get('app_debug');
    }

    /**
     * @param $language_id
     * @param $category_id
     * @return array|\PDOStatement|string|\think\Collection|\think\model\Collection
     */
    public function popularProduct($language_id, $category_id)
    {
        try {
            if($this->debug ==false){
                $data=Cache::get(__FUNCTION__.$language_id.$category_id);
                if($data) return $data;
                $obj=(new ProductCategory())->popularProduct($category_id);
                Cache::set(__FUNCTION__.$language_id.$category_id,$obj);
                return $obj;
            }
            return (new ProductCategory())->popularProduct($category_id);
        } catch (\Exception $exception) {
            if ($this->debug == true) Log::debug('controller' . __FUNCTION__ . ':' . $exception);
        }
    }
}