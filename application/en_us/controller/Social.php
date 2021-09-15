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
// use app\common\service\en_us\SocialResume;
use app\common\model\Jobs\SocialResume;
use think\App;
use think\paginator\driver\Bootstrap;

class Social extends Base
{
    protected $service;
    protected $data;
    /**
     * Undocumented function
     *
     * @Author: kevin qiu
     * @DateTime: 2021-08-30
     * @param [type] $app
     */
    public function __construct(App $app = NULL)
    {
        parent::__construct($app);
        $this->service = new service();
        $this->data = $this->service->gedJobsByStatus((array)$status = [0, 1]);
    }
    public function index()
    {
        if ($this->request->isGet()) {
            if (!empty($this->data)) {
                $hot = array_slice($this->data, 0, 12);
                $this->assign('hot', $hot);
            }
            return $this->fetch($this->template . '/social/index.html');
        }
    }
    /**
     * Undocumented function
     *
     * @Author: kevin qiu
     * @DateTime: 2021-08-30
     * @return void
     */
    public function list()
    {
        # code...
        if ($this->request->isGet()) {
            $data = $this->data;
            $count = count($data);
            $pages = input('page', 1);
            $size = 12;
            $page_options = ['var_page' => 'page', 'path' => '/' . $this->code . '/jobs/social/list']; //分页选项
            $page = Bootstrap::make($data, $size, $pages, $count, true, $page_options);
            if (!empty($data)) {
                $hot = array_slice($data, 0, 5);
                $this->assign('hot', $hot);
            }
            $this->assign('data', array_slice($data, ($pages - 1) * $size, $size));
            $this->assign('page', $page);
            return $this->fetch($this->template . '/social/list.html');
        }
    }
    /**
     * Undocumented function
     *
     * @Author: kevin qiu
     * @DateTime: 2021-08-30
     * @return void
     */
    function details()
    {
        # code...
        if ($this->request->isGet()) {
            if (!empty($this->data)) {
                $hot = array_slice($this->data, 0, 5);
                $this->assign('hot', $hot);
            }
            $url_title = $this->request->param('url_title');
            $data = $this->service->getDetails((string)$url_title);
            $this->assign('data', $data);
            return $this->fetch($this->template . '/social/details.html');
        }
    }
    /**
     * Undocumented function
     *
     * @Author: kevin qiu
     * @DateTime: 2021-08-30
     * @return void
     */
    function gain()
    {
        if ($this->request->isGet()) {
            $url_title = $this->request->param('url_title');
            $this->assign('url_title', $url_title);
            return $this->fetch($this->template . '/social/gain.html');
        }
        if ($this->request->isPost()) {
            //todo:: 申请步骤
            $data = input('post.', 'htmlspecialchars');
            try {
                $result = (new SocialResume())->save($data);
                if ($result) {
                    return show(1, '', '', '', '', '申请成功');
                } else {
                    return show(0, '', '', '', '', '申请失败');
                }
            } catch (\Exception $exception) {
                return show(0, '', '', '', '', $exception->getMessage());
            }
        }
    }
}