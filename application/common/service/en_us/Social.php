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
    public function __construct()
    {
        parent::__construct();
        $this->model = new model();
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
            if ($this->debug == true) Log::error(__FUNCTION__ . ':' . $exception->getMessage());
            return $exception->getMessage();
        }
    }
}