<?php

declare(strict_types=1);
/**
 * @Create by vscode,
 * @author:jinxiu89@163.com
 * @Create Date:2021年08月27日 15:51:24 星期五
 * @User: admin
 * @Current File : Social.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\en_us\controller;

use app\common\service\en_us\Social as service;
use think\App;
use think\paginator\driver\Bootstrap;

class Social extends Base
{
    protected $service;
    public function __construct(App $app = NULL)
    {
        parent::__construct($app);
        $this->service = new service();
    }
    public function index()
    {
        return $this->fetch($this->template . '/social/index.html');
    }
    public function list()
    {
        # code...
        if ($this->request->isGet()) {
            $data = $this->service->gedJobsByStatus((array)$status = [0, 1]);
            $count = count($data);
            $pages = input('page', 1);
            $size = 12;
            $page_options = ['var_page' => 'page', 'path' => $this->code . '/jobs/social/list']; //分页选项
            $page = Bootstrap::make($data, $size, $pages, $count, true, $page_options);
            $this->assign('data', array_slice($data, ($pages - 1) * $size, $size));
            $this->assign('page', $page);
            print_r($page);
            return $this->fetch($this->template . '/social/list.html');
        }
    }
    function details()
    {
        # code...
        return $this->fetch($this->template . '/social/details.html');
    }
    function gain()
    {
        return $this->fetch($this->template . '/social/gain.html');
    }
}