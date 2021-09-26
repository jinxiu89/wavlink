<?php

declare(strict_types=1);
/**
 * @Create by vscode,
 * @author:jinxiu89@163.com
 * @Create Date:2021年08月24日 11:01:07 星期二
 * @User: admin
 * @Current File : Category.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\common\model\Jobs;

use app\common\model\BaseModel;
use think\Db;

/**
 * 招聘管理系统的职位分类表
 * 表名：tb_jobs_category
 * @Author: kevin qiu
 * @DateTime: 2021-08-24
 */
class Category extends BaseModel
{
    protected $table = 'tb_jobs_category';

    public function getCategory()
    {
        return Db::table($this->table)->field('id,name,title')->all();
    }
}