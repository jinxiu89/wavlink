<?php

declare(strict_types=1);
/**
 * @Create by vscode,
 * @author:jinxiu89@163.com
 * @Create Date:2021年08月27日 16:10:02 星期五
 * @User: admin
 * @Current File : Social.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\common\service\en_us;

use app\common\service\en_us\BaseService;
use app\common\model\Jobs\Social as model;
use app\common\model\Jobs\Category;
use think\facade\Cache;
use think\Exception;
use think\Log;

/**
 * Undocumented class
 *
 * @Author: kevin qiu
 * @DateTime: 2021-08-27
 */
class Social extends BaseService
{
    protected $category_model;
    public function __construct()
    {
        parent::__construct();
        $this->model = new model();
        $this->category_model = new Category();
    }
    public function gedJobsByStatus(array $status)
    {
        try {
            if (false == $this->debug) {
                $data = Cache::get(__FUNCTION__);
                if ($data) return $data;
                $obj = $this->model->gedJobsByStatus((array)$status);
                Cache::set(__FUNCTION__, $obj);
                return $obj;
            }
            return $this->model->gedJobsByStatus((array)$status);
        } catch (Exception $exception) {
            // if ($this->debug == true) Log::error(__FUNCTION__ . ':' . $exception->getMessage());
            return $exception->getMessage();
        }
    }

    public function getCategory()
    {
        try {
            return $this->category_model->getCategory();
        } catch (Exception $exception) {
            return ['err' => $exception->getMessage()];
        }
    }
    public function getCity()
    {
        try {
            return array_unique($this->model->getCity());
        } catch (Exception $exception) {
            return [$exception->getMessage()];
        }
    }

    public function getDataByWhere(array $where = [])
    {
        if (empty($where)) {
            return $this->gedJobsByStatus((array)$status = [0, 1]);
        }
        try {
            return $this->model->getDataByWhere((array)$where, (array)$status = [0, 1]);
        } catch (Exception $exception) {
            return [];
        }
    }
    /**
     * getDetails  获取简历详情
     *
     * @Author: kevin qiu
     * @DateTime: 2021-09-07
     * @param string $url_title
     * @return void
     */
    public function getDetails(string $url_title)
    {
        try {
            if (false == $this->debug) {
                $data = Cache::get(__FUNCTION__);
                if ($data) return $data;
                $obj = $this->model->getDetails((string)$url_title);
                Cache::set(__FUNCTION__, $obj);
                return $obj;
            }
            return $this->model->getDetails((string)$url_title);
        } catch (Exception $exception) {
            if ($this->debug == true) Log::error(__FUNCTION__ . ':' . $exception->getMessage());
            return $exception->getMessage();
        }
    }
}