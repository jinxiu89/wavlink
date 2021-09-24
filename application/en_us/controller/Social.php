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
use app\common\model\Jobs\SocialResume;
use app\common\helper\World2Html;
use Exception;
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
     * 简历上传接口，只要是WORD文档都转一遍
     *
     * @Author: kevin qiu
     * @DateTime: 2021-09-23
     * @return void
     */
    public function upload_resume()
    {
        if ($this->request->isPost()) {
            $file = $this->request->file('file');
            try {
                $info = $file->validate(['ext' => 'pdf,doc,docx,zip,rar'])->rule('uniqid')->move(PUBLIC_PATH . '/hr');
                if ($info) {
                    if ($info->getInfo()['type'] === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' or $info->getInfo()['type'] === 'application/msword') {
                        $result = (new World2Html())->Libreoffice(PUBLIC_PATH . '/hr/' . $info->getSaveName(), PUBLIC_PATH . '/hr/');
                        if ($result) {
                            $str = $info->getSaveName();
                            $path = explode('.', $str, 0)[0] . '.pdf';
                            if (unlink(PUBLIC_PATH . '/hr/' . $str)) return jsonShow((int)200, (string)$message = "success", (array) $data = ['path' => $path]);
                            return jsonShow((int) 500, (string)$message = "upload failed", (array) $data = []);
                        }
                    }
                    return jsonShow((int)200, (string)$message = "success", (array) $data = ['path' => $info->getSaveName()]);
                } else {
                    return jsonShow((int) 500, (string)$message = $file->getError(), (array) $data = []);
                }
                return jsonShow((int) 500, (string)$message = "upload failed", (array) $data = []);
            } catch (Exception $exception) {
                return jsonShow((int) 500, (string)$message = $exception->getMessage(), (array) $data = []);
            }
        }
    }
    /**
     * hello  testify
     *
     * @Author: kevin qiu
     * @DateTime: 2021-09-23
     * @return void
     */
    public function testify()
    {
        if ($this->request->isGet()) {
            return $this->fetch($this->template . '/social/testify.html');
        }
        return jsonShow(500, $message = '访问方法不合法', $data = []);
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