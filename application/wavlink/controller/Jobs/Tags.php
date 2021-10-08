<?php

declare(strict_types=1);
/**
 * @Create by vscode,
 * @author:jinxiu89@163.com
 * @Create Date:2021年10月06日 16:29:31 星期三
 * @User: admin
 * @Current File : Tags.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\wavlink\controller\Jobs;

use app\wavlink\controller\BaseAdmin;
use app\common\model\Jobs\Tags as Model;
use app\wavlink\validate\jobs\Tags as tValidate;
use think\App;

class Tags extends BaseAdmin
{
    protected $validate;
    protected $model;
    public function __construct(APP $app = NULL)
    {
        parent::__construct($app);
        $this->validate = new tValidate();
        $this->model = new Model();
    }
    /**
     * 
     *
     * @Author: kevin qiu
     * @DateTime: 2021-10-06
     * @return void
     */
    public function index()
    {
        if ($this->request->isGet()) {
            return $this->fetch();
        }
    }
    /**
     * 添加tags
     *
     * @Author: kevin qiu
     * @DateTime: 2021-10-06
     * @return void
     */
    public function add()
    {
        if ($this->request->isGet()) {
            return $this->fetch();
        }
        if ($this->request->isPost()) {
            $data = input('post.', 'htmlentities');
            $resume_id = $data['id'];
            unset($data['id']);
            if (!$this->validate->scene('add')->check($data)) {
                return show(0, '', '', '', '', $this->validate->getError());
            }
            try {
                $res = $this->model->create($data);
                if (is_object($res)) {
                    return show(1, '添加成功', '', '', '', $res->id);
                } else {
                    return show(0, '', '', '', '', '添加失败');
                }
            } catch (\Exception $exception) {
                return show(0, '', '', '', '', $exception->getMessage());
            }
        }
    }
}