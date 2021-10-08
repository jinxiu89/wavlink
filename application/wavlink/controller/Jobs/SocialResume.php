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
use app\common\model\Jobs\Tags;
use app\common\helper\World2Html;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use think\Db;
use think\facade\Env;

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
            $data = $this->service->getData();
            $tags = Db::table('tb_resume_tags')->field('id,tags')->select();
            if (!$data['data']->isEmpty()) {
                $this->assign('page', $data['data']->render());
                $this->assign('data', $data['data']);
                $this->assign('tags', $tags);
                $this->assign('count', $data['count']);
            }
            return $this->fetch();
        }
    }
    /**
     * 预览简历
     *
     * @Author: kevin qiu
     * @DateTime: 2021-10-08
     * @return void
     */
    public function views()
    {
        if ($this->request->isGet()) {
            return $this->fetch();
        }
    }
    /**
     * 状态值定义
     * 0、未读
     * 1、已读
     * -99、删除
     *
     * @Author: kevin qiu
     * @DateTime: 2021-09-30
     * @return void
     */
    public function readed()
    {
        if ($this->request->isGet()) {
            $id = $this->request->param('id');
            $res = $this->service->readed((int)$id, (int)1);
            print_r($res);
            exit;
        }
    }
    /**
     * 给简历加标签
     *
     * @Author: kevin qiu
     * @DateTime: 2021-10-08
     * @return void
     */
    public function add_tag()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            try {
                $res = $this->service->add_tag((array)$data);
                if ($res) {
                    return show(1, '添加标签成功', '', '', '', '添加标签成功');
                } else {
                    return show(0, '添加标签失败', '', '', '', '添加标签失败');
                }
            } catch (\Exception $exception) {
                return show(0, $exception->getMessage(), '', '', '', $exception->getMessage());
            }
        }
    }
}