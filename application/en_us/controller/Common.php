<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/4/15 17:34
 * @User: admin
 * @Current File : Common.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\en_us\controller;


use think\App;
use think\Controller;
use think\facade\Cookie;
use think\response\Redirect;

/**
 * Class Common
 * @package app\en_us\controller
 */
class Common extends Controller
{
    protected $lang;
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->lang=Cookie::get('lang_var') ? Cookie::get('lang_var') : 'en_us';
    }

    /**
     * @return mixed
     */
    public function driver()
    {

        return $this->fetch();
    }

    /**
     * @return Redirect
     */
    public function manual()
    {
        return \redirect('/' . $this->lang . '/manuals.html');
    }
}