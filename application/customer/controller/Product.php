<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/4/2 14:41
 * @User: admin
 * @Current File : product.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\customer\controller;


use app\common\service\customer\Product as Service;
use app\customer\validate\Product as Validate;
use think\App;
use think\facade\Cookie;
use app\customer\middleware\Auth;

/**
 * Class product
 * @package app\customer\controller
 */
class Product extends Base
{
    /**
     * product constructor.
     * @param App|null $app
     */
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->validate = new Validate();
        $this->service = new Service();
    }

    protected $middleware = [
        Auth::class => ['except' => ['register']]
    ];

    /**
     * @return mixed
     */
    public function lists()
    {
        if ($this->request->isGet()) {
            $data = $this->service->getProductByUid($this->uid);
            if (!$data->isEmpty()) {
                $this->assign('page', $data->render());
                $this->assign('data', $data);
                $this->assign('count', $data->count());
            }
            return $this->fetch();
        }
    }

    /**
     * register 在用户注册时 的第二步注册产品 不需要验证是否登录
     * @return mixed|void
     */
    public function register()
    {
        if (request()->isGet()) {
            $country = $this->service->getCountry();
            $code = $this->code;
            $category = $this->service->getCategory($code);
            return $this->fetch('', ['user_id' => input('get.user_id'), 'country' => $country, 'category' => $category]);
        }
        if (request()->isAjax()) {
            $data = input('post.', [], 'htmlspecialchars,trim');
            $data['create_time'] = strtotime($data['create_time']);
            if (!$this->validate->scene('add')->check($data)) {
                return show(0, '', '', '', '', $this->validate->getError());
            }
            $instance = $this->service->create($data); //instance 是实例的意思
            if ($instance->id) {
                return show(1, lang('Success'), '', '', url('customer_login'), lang('Successfully!'));
            } else {
                return show(0, lang('Error'), '', '', '', lang('Failed!'));
            }
        }
    }

    /**
     * addProduct
     * @return mixed
     */
    public function addProduct()
    {
        if ($this->request->isGet()) {
            $country = $this->service->getCountry();
            $code = $this->code;
            $category = $this->service->getCategory($code);
            return $this->fetch('', ['user_id' => $this->uid, 'country' => $country, 'category' => $category]);
        }
        if ($this->request->isPost()) {
            $data = input('post.', [], 'htmlspecialchars,trim');
            $data['create_time'] = strtotime($data['create_time']);
            if (!$this->validate->scene('add')->check($data)) {
                return show(0, '', '', '', '', $this->validate->getError());
            }
            $instance = $this->service->create($data); //instance 是实例的意思
            if ($instance->id) {
                return show(1, lang('Success'), '', '', url(''), lang('Successfully!'));
            } else {
                return show(0, lang('Error'), '', '', '', lang('Failed!'));
            }
        }
    }

    public function delete()
    {
        if ($this->request->isPost()) {
            $data = input('post.', [], 'htmlspecialchars,trim');
            if (!$this->validate->scene('delete')->check($data)) {
                return show(0, '', '', '', '', $this->validate->getError());
            }
            $instance = $this->service->del($data['id']);
            if ($instance == true) {
                return show(1, lang('Success'), '', '', url(''), lang('Successfully!'));
            } else {
                return show(0, lang('Error'), '', '', '', lang('Failed!'));
            }
        }
    }
}
