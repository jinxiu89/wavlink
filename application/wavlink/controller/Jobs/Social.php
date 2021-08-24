<?php

declare(strict_types=1);
/**
 * @Create by vscode,
 * @author:jinxiu89@163.com
 * @Create Date:2021年08月18日 18:52:47 星期三
 * @User: admin
 * @Current File : Social.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\wavlink\controller\Jobs;

use app\wavlink\controller\BaseAdmin;

/**
 * s社招职位路由
 * 
 * @Author: kevin qiu
 * @DateTime: 2021-08-24
 */
class Social extends BaseAdmin
{
    /**
     * Route::rule('/jobs/social$', 'Jobs.Social/index')->name('jobs_social');
     * 
     *
     * @Author: kevin qiu
     * @DateTime: 2021-08-24
     * @return void
     */
    public function index()
    {
        return $this->fetch('');
    }
    public function list()
    {
        return $this->fetch('');
    }
    public function add()
    {
        return $this->fetch('');
    }
    public function edit()
    {
        return $this->fetch('');
    }
    public function stop()
    {
    }
    public function delete()
    {
    }
}