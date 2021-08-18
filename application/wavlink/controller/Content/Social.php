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

namespace app\wavlink\controller\Content;

use app\wavlink\controller\BaseAdmin;

class Social extends BaseAdmin
{
    public function index()
    {
        print_r("社招首页");
    }
    public function list()
    {
        print_r("hello 列表");
    }
    public function add()
    {
        print_r("添加职位");
    }
    public function edit()
    {
    }
    public function stop()
    {
    }
    public function delete()
    {
    }
}