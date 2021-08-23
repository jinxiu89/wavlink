<?php

declare(strict_types=1);
/**
 * @Create by vscode,
 * @author:jinxiu89@163.com
 * @Create Date:2021年08月23日 18:39:37 星期一
 * @User: admin
 * @Current File : category.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\wavlink\controller\Jobs;

use app\wavlink\controller\BaseAdmin;

class Category extends BaseAdmin
{
    public function index()
    {
        return $this->fetch();
    }
    public function add()
    {
        return $this->fetch();
    }
}