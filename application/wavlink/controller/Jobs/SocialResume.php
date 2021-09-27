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
use app\common\helper\World2Html;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
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
            if (!$data['data']->isEmpty()) {
                $this->assign('page', $data['data']->render());
                $this->assign('data', $data['data']);
                $this->assign('count', $data['count']);
            }
            // print_r($data['data']);?
            return $this->fetch();
        }
    }

    public function views($url)
    {
        if ($this->request->isGet()) {
            $path = PUBLIC_PATH . '/hr/' . $url;
            print_r($path);
            return $this->fetch();
        }
        // $html = (new World2Html())->World2Html($path);
        // $temp = $html->save($htmlFile);
        // print_r($html);
        // exit;https://cdn.bootcdn.net/ajax/libs/pdf.js/2.10.377/images/annotation-check.svg
        // $html = IOFactory::load($path); /hr/20210915/7afa6141631cba79ef2ac87666e72e49.pdf

        // 
        // $html->save($htmlFile);
        // $temp = file_get_contents($htmlFile);
        // print_r($html);
        // 85900db0485b21ae3ad0632a3349cf12.doc
    }
}