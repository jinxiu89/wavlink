<?php

declare(strict_types=1);
/**
 * @Create by vscode,
 * @author:jinxiu89@163.com
 * @Create Date:2021年09月08日 18:00:51 星期三
 * @User: admin
 * @Current File : SocialResume.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\wavlink\controller\Jobs;

use app\wavlink\controller\BaseAdmin;
use think\App;
use app\wavlink\service\contents\SocialResume as service;

/**
 * 后台简历操作窗口
 *
 * @Author: kevin qiu
 * @DateTime: 2021-09-09
 */
class SocialResume extends BaseAdmin
{
    protected $service;
    /**
     * 
     *
     * @Author: kevin qiu
     * @DateTime: 2021-09-09
     * @param [type] $app
     */
    public function __construct(App $app = NULL)
    {
        parent::__construct($app);
        $this->service = new service();
    }
    /**
     * 社招简历列表
     *
     * @Author: kevin qiu
     * @DateTime: 2021-09-09
     * @return void
     */
    public function index()
    {
        if ($this->request->isGet()) {

            return $this->fetch();
        }
    }
}