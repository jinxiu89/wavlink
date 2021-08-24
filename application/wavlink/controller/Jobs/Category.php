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
use app\wavlink\validate\jobs\Category as validate;
use app\common\model\Jobs\Category as Model;
use think\App;

class Category extends BaseAdmin
{
    protected $validate;
    protected $model;

    public  function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->validate = new validate();
        $this->model = new Model();
    }
    public function index()
    {
        $data = $this->model->all()->toArray();
        $this->assign('data', $data);
        return $this->fetch();
    }
    public function add()
    {
        if ($this->request->isGet()) {
            return $this->fetch();
        }
        if ($this->request->isPost()) {
            //接受数据
            $data = input('post.', 'htmlentities');
            if ($this->validate->scene('add')->check($data)) {
                try {
                    $res = $this->model->save($data);
                    if ($res) {
                        return show(1, '', '', '', '', '添加成功');
                    } else {
                        return show(0, '', '', '', '', '添加失败');
                    }
                } catch (\Exception $exception) {
                    return show(0, '', '', '', '', $exception->getMessage());
                }
            }
            return show(0, '', '', '', '', $this->validate->getError());
        }
    }

    public function edit()
    {
        $id = $this->request->param('id');
        # code...
        if ($this->request->isGet()) {
            $data = $this->model->get(['id' => $id]);
            $this->assign('data', $data);
            return $this->fetch();
        }
        if ($this->request->isPost()) {
            $data = input('post.', 'htmlentities');
            if ($this->validate->scene('edit')->check($data)) {
                try {
                    $res = $this->model->save($data, ['id' => $id]);
                    if ($res) {
                        return show(1, '', '', '', '', '修改成功');
                    } else {
                        return show(0, '', '', '', '', '修改失败');
                    }
                } catch (\Exception $exception) {
                    return show(0, '', '', '', '', $exception->getMessage());
                }
            }
            return show(0, '', '', '', '', $this->validate->getError());
        }
    }
}