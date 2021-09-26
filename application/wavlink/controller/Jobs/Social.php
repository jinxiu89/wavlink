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
use think\App;
use app\common\model\Jobs\Social as Model;
use app\wavlink\validate\jobs\Social as validate;
use app\common\model\Jobs\Category;
use Exception;

/**
 * s社招职位路由
 * 
 * @Author: kevin qiu
 * @DateTime: 2021-08-24
 */
class Social extends BaseAdmin
{

    protected $validate;
    protected $model;
    protected $category;
    /**
     * 初始化函数
     *
     * @Author: kevin qiu
     * @DateTime: 2021-08-25
     * @param App $app
     */
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->validate = new validate();
        $this->model = new Model();
        $this->category = new Category;
    }
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
        if ($this->request->isGet()) {
            $data = $this->model->order(['sort' => 'desc', 'create_time' => 'desc', 'id' => 'asc'])->all();
            $this->assign('data', $data->toArray());
            return $this->fetch('');
        }
    }
    public function list()
    {
        return $this->fetch('');
    }
    /**
     * 职位添加路由
     *
     * @Author: kevin qiu
     * @DateTime: 2021-08-25
     * @return void
     */
    public function add()
    {
        if ($this->request->isGet()) {
            $category = (new Category())->all();
            $this->assign('category', $category);
            return $this->fetch('');
        }
        if ($this->request->isPost()) {
            $data = input('post.');
            if ($this->validate->scene('v')->check($data)) {
                $data['url_title'] = substr(md5(uniqid()), 3, 12);
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
        if ($this->request->isGet()) {
            $category = (new Category())->all();
            $data = $this->model->get(['id' => $id])->toArray();
            $this->assign('data', $data);
            $this->assign('category', $category);
            return $this->fetch('');
        }
        if ($this->request->isPost()) {
            $data = input('post.');
            if ($this->validate->scene('edit')->check($data)) {
                try {
                    $res = $this->model->save($data, ['id' => $id]);
                    if ($res) {
                        return show(1, '', '', '', '', '保存成功');
                    } else {
                        return show(0, '', '', '', '', '保存失败');
                    }
                } catch (\Exception $exception) {
                    return show(0, '', '', '', '', $exception->getMessage());
                }
            }
            return show(0, '', '', '', '', $this->validate->getError());
        }
    }
    /**
     * 后台排序算法
     *
     * @Author: kevin qiu
     * @DateTime: 2021-09-24
     * @return void
     */
    public function sort()
    {
        if ($this->request->isPost()) {
            $id = $this->request->param('id');
            if ($this->validate->scene('sort')->check(['id' => $id])) {
                try {
                    $sort = $this->model->max('sort');
                    $sort = (int)$sort + 1;
                    if ($this->model->sort((int)$id, (int)$sort)) {
                        return show(1, '', '', '', '', '排序成功');
                    }
                } catch (Exception $exception) {
                    return show(0, '', '', '', '', $exception->getMessage());
                }
            }
        }
    }
    public function stop()
    {
    }
    public function delete()
    {
    }
}