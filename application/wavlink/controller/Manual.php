<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/12/18
 * Time: 17:10
 */

namespace app\wavlink\controller;

use app\common\model\Manual as ManualModel;
use app\common\model\ManualDownload;
use app\common\model\ServiceCategory as ServiceCategoryModel;
use app\wavlink\validate\UrlTitleMustBeOnly;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Request;

/**
 * Class Manual
 * @package app\wavlink\controller
 */
class Manual extends BaseAdmin
{
    protected $language_id;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->language_id = $this->currentLanguage['id'];
        $this->assign('language_id', $this->language_id);
    }

    /**
     * @return mixed
     * 20190917 更新
     *
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
     * @throws DbException
     * 20190917 更新
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
     * @throws \app\lib\exception\ParameterException
     *
     */
    public function save()
    {
        if (request()->isAjax()) {
            (new UrlTitleMustBeOnly())->goCheck();
            (new \app\wavlink\validate\Manual())->goCheck();
            $data = input('post.');
            $data['status'] = 1;
            if (!empty($data['id'])) {
                return $this->update($data);
            } else {
                try {
                    (new ManualModel())->add($data);
                    return show(1, '', '', '', '', '添加成功');
                } catch (\Exception $e) {
                    return show(0, '', '', '', '', $e->getMessage());
                }
            }
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
                (new \app\wavlink\validate\ManualDownload())->scene('update')->goCheck();
                $data['update_time'] = date('Y-m-d', time());
                try {
                    (new ManualDownload())->allowField(true)->save($data, ['id' => $data['id']]);
                    return show(1, '', '', '', '', '更新成功！');

                } catch (\Exception $e) {
                    return show(0, '', '', '', '', $e->getMessage());
                }
            } else {
                (new \app\wavlink\validate\ManualDownload())->scene('add')->goCheck();
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