<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/18
 * Time: 16:14
 */

namespace app\wavlink\controller;

use think\App;
use think\Controller;
use app\common\model\Language;
use think\Collection;
use app\common\model\System\Manger;

/**
 * Class Login
 * @package app\wavlink\controller
 */
class Login extends Controller
{
    protected $next;
    protected $model;
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->model=new Manger();
    }

    public function index()
    {
        if (input('next')) {
            $this->next = input('next');
        } else {
            $this->next = url('/wavlink/index');
        }
        if (request()->isPost()) {//提交登录
            //登录的逻辑
            //获取相关的数据
            $data = input('post.');
            //通过用户名 获取用户相关信息
            //严格的判断

            //用户名判断
            $ret = $this->model->get(['username' => $data['username']]);
            if (!$ret || $ret->status != 1) {
                return show(0, '', '', '', '', '用户异常，不存在或者被禁用');
            }
            //验证密码
            if ($ret->password != md5($data['password'] . $ret->code)) {
                return show(0, '', '', '', '', '密码不正确');
            }
            //验证码判断
            $config = config('app_debug');
            if (!$config) {
                if (!captcha_check($data['captcha'])) {
                    return show(0, '', '', '', '', '验证码错误');
                }
            }
            $this->model->updateById(['last_login_time' => time(), 'ip' => request()->ip()], $ret->id);
            $cookie['id']=$ret->id;
            $cookie['username']=$ret->username;
            $cookie['name']=$ret->name;
            //保存登陆信息,session 助手函数,第一个参数是变量，给它取变量名'userName'。第二个参数是值，获取到的$ret的值。第三个是作用域，admin模块下登陆信息。
            session('userName', $cookie, 'admin');
            session('current_language', (new Language())->getLanguageByLanguageId($data['language_id'])[0], 'admin');
            return show(1, 'success', '', '', $this->next, '登录成功，请稍后');
//
        } else {
            $language = Collection::make(Language::all())->toArray();
            $user = session('userName', '', 'admin');
            if ($user && !empty($user) && is_array($user)) {
                $this->redirect($this->next);
            }
            return $this->fetch('', ['language' => $language, 'next' => $this->next]);
        }
    }

    public function logout()
    {
        //清除session
        session(null, 'admin');
        //跳出
        $this->redirect('login/index');
    }

}