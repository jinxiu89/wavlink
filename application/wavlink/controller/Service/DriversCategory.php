<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/5/20 14:52
 * @User: admin
 * @Current File : DriversCategory.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\wavlink\controller\Service;


use app\common\helper\Category;
use app\wavlink\controller\BaseAdmin;
use think\App;
use app\wavlink\service\service\driversCategory as service;
use app\wavlink\validate\service\driverCategory as validate;

/**
 * Class DriversCategory
 * @package app\wavlink\controller\Service
 */
class DriversCategory extends BaseAdmin
{

    protected $service;
    protected $validate;


    /**
     * DriversCategory constructor.
     * @param App|null $app
     */
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->service = new service();
        $this->validate = new validate();
        $this->assign('language_id', $this->currentLanguage['id']);
    }


    /**
     * @return mixed
     * 藁本 12g 川芎 12g 防风 12g 苍术 12g 白术 12g 柴胡 12g 羌活 12g 黄芪 12g 干姜 12g 肉桂 12g 有劳累过度（加班或者泡妞太过），怎么补怎么都无效时可以用此方试试。
     */
    public function index()
    {
        if ($this->request->isGet()) {
            $data = $this->service->getDataByLanguageId($status='',$this->currentLanguage['id']);
            $level = Category::toLevel($data['data']->toArray()['data'], '├─');
            $this->assign('page', $data['data']->render());
            $this->assign('data', $level);
            $this->assign('count', $data['count']);
            return $this->fetch();
        }
    }

    /**
     * @return mixed|void
     */
    public function add()
    {
        if ($this->request->isGet()) {
            $category = (new service())->getDataByLanguageId($status=1,$this->currentLanguage['id']);
            $level = Category::toLevel($category['data']->toArray()['data'], '&nbsp;&nbsp;');
            $this->assign('category', $level);
            return $this->fetch();
        }
        if ($this->request->isPost()) {
            $data = input('post.', [], 'trim,htmlspecialchars');
            $data['status'] = 1;
            $data['title'] = substr(md5(uniqid()), 3, 12);
            if ($data['parent_id'] == 0) {
                $data['path'] = '-';
                $data['level'] = 0;
            } else {
                $parent = $this->service->getParent($data['parent_id']);
                $data['level'] = $parent->level + 1;
                $data['path'] = $parent->path . $parent->id . '-';
            }
            if (!$this->validate->scene('add')->check($data)) {
                return show(0, $this->validate->getError(), '', '', '', $this->validate->getError());
            }
            $result = $this->service->create($data);
            if (true == $result) {
                return show(1, '新增成功', '', '', url('driver_category_index'), '新增成功！');
            } elseif (false == $result) {
                return show(0, lang('Unknown Error'), '', '');
            } else {
                return show(0, lang($result), '', '');
            }
        }
    }

    /**
     * @return mixed
     */
    public function edit()
    {
        if ($this->request->isGet()) {
            $category = (new service())->getDataByLanguageId($status=1,$this->currentLanguage['id']);
            $level = Category::toLevel($category['data']->toArray()['data'], '&nbsp;&nbsp;');
            $id = input('get.id', 'intval,trim');
            $data = $this->service->getDataById($id);
            $this->assign('data', $data->toArray());
            $this->assign('category', $level);
            return $this->fetch();
        }
        if ($this->request->isPost()) {
            $data = input('post.', [], 'trim,htmlspecialchars');

            if (!$this->validate->scene('edit')->check($data)) {
                return show(0, $this->validate->getError(), '', '', '', $this->validate->getError());
            }
            if ($data['parent_id'] == 0) {
                $data['path'] = '-';
                $data['level'] = 0;
            } else {
                $parent = $this->service->getParent($data['parent_id']);
                $data['level'] = $parent->level + 1;
                $data['path'] = $parent->path . $parent->id . '-';
            }
            $result = $this->service->update($data);
            if (true == $result) {
                return show(1, '保存成功', '', '', url('driver_category_index'), '保存成功！');
            } elseif (false == $result) {
                return show(0, lang('Unknown Error'), '', '');
            } else {
                return show(0, lang($result), '', '');
            }
        }
    }

    public function ByStatus()
    {
        $data = input('get.');
        if (!$this->validate->scene('changeStatus')->check($data)) {
            return show(0, "failed", '', '', '', $this->validate->getError());
        }
        //todo：：当该分类下有内容时不能被禁用
        try {
            if ($this->service->update($data)) {
                return show(1, "success", '', '', '', '操作成功');
            }
            return show(0, "success", '', '', '', '操作失败！未知原因');
        } catch (\Exception $exception) {
            return show(0, "failed", '', '', '', $exception->getMessage());
        }
    }
}