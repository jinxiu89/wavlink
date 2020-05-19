<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/12/18
 * Time: 17:10
 */

namespace app\wavlink\controller\Service;

use app\common\model\Service\Manual as ManualModel;
use app\common\model\Service\ManualDownload;
use app\common\model\Service\ServiceCategory as ServiceCategoryModel;
use app\wavlink\controller\BaseAdmin;
use app\wavlink\validate\Manual as ManualValidate;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

/**
 * Class Manual
 * @package app\wavlink\controller
 */
class Manual extends BaseAdmin
{
    protected $language_id;
    protected $model;
    protected $validate;
    /**
     * Manual constructor.
     * @param App|null $app
     */
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->language_id = $this->currentLanguage['id'];
        $this->model=new ManualModel();
        $this->validate=new ManualValidate();
        $this->assign('language_id', $this->language_id);
    }

    /**
     * @return mixed
     * 20190917 更新
     * @throws DbException
     */
    public function index()
    {
        $data = ManualModel::getDataByStatus(1, $this->language_id);
        return $this->fetch('', [
            'count' => $data['count'],
            'data' => $data['data'],
        ]);
    }

    /***
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function add()
    {
        $category = ServiceCategoryModel::getSecondCategory($this->language_id);
        return $this->fetch('', ['category' => $category, 'language_id' => $this->language_id,]);
    }

    /***
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * 20190917 更新
     */
    public function edit()
    {
        $category = ServiceCategoryModel::getSecondCategory($this->language_id);
        $data = ManualModel::get(input('get.id'));
        return $this->fetch('', [
            'category' => $category,
            'data' => $data
        ]);
    }

    /**
     * 20200321
     * 优化代码结构
     */
    public function byStatus()
    {
        $data = input('get.');
        if (!$this->validate->scene('changeStatus')->check($data)) {
            return show(0, "failed", '', '', '', $this->validate->getError());
        }
        try {
            if($this->model->checkDownload($data['id']) == false){
                return show(0, "failed", '', '', '', '该条目有下载项，需要核实下载文件是否需要删除');
            }
            if ($this->model->allowField(true)->save($data, ['id' => $data['id']])) {
                return show(1, "success", '', '', '', '操作成功');
            }
            return show(0, "failed", '', '', '', '操作失败！未知原因');
        } catch (\Exception $exception) {
            return show(0, "failed", '', '', '', $exception->getMessage());
        }

    }

    /**
     * @return mixed
     * 20190917 更新
     *
     */
    public function add_download()
    {
        $id = $this->MustBePositiveInteger(input('get.id'));
        return $this->fetch('', ['id' => $id]);
    }

    /**
     * @return mixed
     */
    public function edit_download()
    {
        $id = $this->MustBePositiveInteger(input('get.id'));//需要编辑的ID
        $manual_id = $this->MustBePositiveInteger(input('get.manual_id'));//所属说明书ID
        $result = ManualDownload::get($id);//这里肯定是一组数，所以有问题，我们需要查单个
        return $this->fetch('', ['result' => $result, 'manual_id' => $manual_id]);
    }

    /**
     * @return array|void
     */
    public function save()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            $data['status'] = 1;
            $validate=new ManualValidate();
            if(isset($data['id']) || !empty($data['id'])){
                if($validate->scene('edit')->check($data)){
                    return $this->update($data);
                }
            } else {
                if($validate->scene('add')->check($data)){
                    try {
                        (new ManualModel())->add($data);
                        return show(1, '', '', '', '', '添加成功');
                    } catch (\Exception $e) {
                        return show(0, '', '', '', '', $e->getMessage());
                    }
                }
            }
            return show(0, '', '', '', '', $validate->getError());
        }
    }

    /***
     * 保存下载地址项
     * 20190917更新
     */
    public function save_download()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            $data['status'] = 1;
            if (!empty($data['id'])) {
                (new \app\wavlink\validate\ManualDownload())->scene('update')->check($data);
                $data['update_time'] = date('Y-m-d', time());
                try {
                    (new ManualDownload())->allowField(true)->save($data, ['id' => $data['id']]);
                    return show(1, '', '', '', '', '更新成功！');

                } catch (\Exception $e) {
                    return show(0, '', '', '', '', $e->getMessage());
                }
            } else {
//                print_r("hello world");exit;
                (new \app\wavlink\validate\ManualDownload())->scene('add')->check($data);
                try {
                    (new ManualDownload())->addManual($data);
                    return show(1, '', '', '', '', '添加成功');
                } catch (\Exception $e) {
                    return show(0, '', '', '', '', $e->getMessage());
                }
            }
        }
    }

    /***
     * 删除 执行后delete相当于硬删除
     * 20190917
     */
    public function del_download()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            try {
                (new ManualDownload())->where(['id' => $data['id']])->delete();
                return show(1, '', '', '', '', '删除成功！');
            } catch (\Exception $e) {
                return show(0, '', '', '', '', $e->getMessage());
            }
        }
    }
}