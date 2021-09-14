<?php

declare(strict_types=1);
/**
 * @Create by vscode,
 * @author:jinxiu89@163.com
 * @Create Date:2021年09月09日 13:56:35 星期四
 * @User: admin
 * @Current File : SocialResume.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\wavlink\service\contents;

use app\wavlink\service\Base;
use app\common\model\Jobs\SocialResume as model;

class SocialResume extends Base
{
    protected $model;
    /**
     * Undocumented function
     * @Author: kevin qiu
     * @DateTime: 2021-09-09
     */
    public function __construct()
    {
        $this->model =  new model();
    }
    public function getData()
    {
        try {
            $response = $this->model->order('id desc');
            $result['count'] = $response->count();
            $result['data'] = $response->paginate(25);
            return $result;
        } catch (\Exception $exception) {
            //todo:日志 错误

        }
    }
}