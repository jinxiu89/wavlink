<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/11/28 15:50
 * @User: kevin
 * @Current File : Base.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\api\controller;


use think\App;
use think\Controller;

/**
 * Class Base
 * @package app\api\controller
 */
class Base extends Controller
{
    public function __construct(App $app = null)
    {
        parent::__construct($app);
    }
    public function initialize()
    {
        parent::initialize();
    }
}