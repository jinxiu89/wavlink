<?php

declare(strict_types=1);
/**
 * @Create by vscode,
 * @author:jinxiu89@163.com
 * @Create Date:2021年08月24日 17:37:04 星期二
 * @User: admin
 * @Current File : Social.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\common\model\Jobs;

use app\common\model\BaseModel;
use think\Db;

/**
 * 社招职位 模型 class
 *
 * @Author: kevin qiu
 * @DateTime: 2021-08-24
 */
class Social extends BaseModel
{
    protected $table = 'tb_social_jobs';
    public function gedJobsByStatus(array $status)
    {
        return Db::table($this->table)
            ->where('status', 'in', $status)
            ->field('id,title,city,type,salary,seniority,education,status,numbers,update_time')
            ->select();
    }
}