<?php
/**
 * Created by PhpStorm.
 * User: kevin qiu
 * Date: 2019/8/23
 * Time: 10:37
 * 固件
 */

namespace app\wavlink\controller\Service;


use app\common\model\Firmware as model;
use app\wavlink\controller\BaseAdmin;
use app\wavlink\validate\Firmware as validate;
use think\App;
use think\facade\Request;

/**
 * Class Firmware
 * @package app\wavlink\controller
 *
 */
class Firmware extends BaseAdmin
{
    protected $model;
    protected $validate;
    protected $nexturl;

    /**
     * Firmware constructor.
     * @param App|null $app
     * 依赖注入
     */
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->model = new model();
        $this->validate = new validate();
        $this->nexturl = Request::header('Referer');
    }

    /**
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        if (Request::isGet()) {
            $result = $this->model->getDataByLanguageId($this->currentLanguage['id'], 1);
            return $this->fetch('', [
                'data' => $result['data'],
                'count' => $result['count']
            ]);
        }
    }

    /**
     * @return mixed
     * title 支持随机加密生成，后面就省去了研究这个title怎么拼了
     *
     */
    public function add()
    {
        if (Request::isGet()) {
            return $this->fetch('', [
                'language' => $this->currentLanguage['id'],
                'title' => substr(md5(time() . uniqid(microtime(true), true)), 3, 10)
            ]);
        }
        if (Request::isPost()) {
            $data = input('post.', '', 'htmlentities');
            if (!$this->validate->scene('add')->check($data)) {
                return show(0, 'failed', '', '', '', $this->validate->getError());
            }
            try {
                if ($this->model->add($data)) {
                    return show(1, 'ok', '', '', '', '保存成功！');
                }
                return show(0, 'failed', '', '', '', '保存失败，未知原因');
            } catch (\Exception $exception) {
                return show(0, 'failed', '', '', '', $exception->getMessage());
            }
        }
    }

    public function edit()
    {
        if (Request::isGet()) {
            $id=input('get.id');
            if(!$this->validate->scene('id')->check(['id'=>$id])){
                $this->error($this->validate->getError());
            }
            $data = $this->model->get($id);
            return $this->fetch('', ['data' => $data]);
        }
        if (Request::isPost()) {
            $data = input('post.', '', 'trim');
            if (!$this->validate->scene('edit')->check($data)) {
                return show(0, 'failed', '', '', '', $this->validate->getError());
            }
            try {
                if ($this->model->save($data, ['id' => $data['id']])) {
                    return show(1, 'ok', '', '', '', '更新成功！');
                }
                return show(0, 'failed', '', '', '', '更新失败，未知原因');
            } catch (\Exception $exception) {
                return show(0, 'failed', '', '', '', $exception->getMessage());
            }
        }
    }

    /**
     * @return mixed
     */
    public function recycle()
    {
        if (Request::isGet()) {
            try {
                $result = $this->model->getDataByLanguageId($this->currentLanguage['id'], -1);
                return $this->fetch('', ['data' => $result['data'], 'count' => $result['count']]);
            } catch (\Exception $exception) {
                $this->error($exception->getMessage());
            }
        }
    }

    public function byStatus()
    {
        $data = input('get.');
        if (!$this->validate->scene('changeStatus')->check($data)) {
            return show(0, "failed", '', '', '', $this->validate->getError());
        }
        try {
            if ($this->model->allowField(true)->save($data, ['id' => $data['id']])) {
                return show(1, "success", '', '', '', '操作成功');
            }
            return show(0, "success", '', '', '', '操作失败！未知原因');
        } catch (\Exception $exception) {
            return show(0, "failed", '', '', '', $exception->getMessage());
        }
    }

    public function del()
    {
        if(Request::isPost()){
            $id = input('get.id');
            if (!$this->validate->scene('id')->check(['id'=>$id])) {
                return show(0, "failed", '', '', '', $this->validate->getError());
            }
            try {
                $data = $this->model->get($id);
                if ($data->delete(true)) {
                    return show(1, "success", '', '', '', '操作成功');
                }
                return show(0, "success", '', '', '', '操作失败！未知原因');
            } catch (\Exception $exception) {
                return show(0, "failed", '', '', '', $this->validate->getError());
            }
        }
    }
}