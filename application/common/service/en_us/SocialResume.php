<?php

declare(strict_types=1);
/**
 * @Create by vscode,
 * @author:jinxiu89@163.com
 * @Create Date:2021年09月07日 16:51:31 星期二
 * @User: admin
 * @Current File : SocialResume.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\common\service\en_us;

use app\common\model\Jobs\SocialResume as model;

/**
 * 投递简历表服务
 *
 * @Author: kevin qiu
 * @DateTime: 2021-09-07
 */
class SocialResume extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new model();
    }
}