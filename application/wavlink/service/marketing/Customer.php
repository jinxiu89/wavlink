<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/5/11 11:59
 * @User: admin
 * @Current File : Customer.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\wavlink\service\marketing;

use app\wavlink\service\Base;
use app\common\model\Customer\User;
use think\paginator\driver\Bootstrap;

/**
 * Class Customer
 * @package app\wavlink\service\marketing
 * 后台服务逻辑
 */
class Customer extends Base
{
    public function __construct()
    {
        $this->model = new User();
    }

    /**
     * @return mixed
     * 不带任何参数的首页列表分页
     */
    public function getData()
    {
        try {
            $response = $this->model->order('id desc')->with('info');
            $result['count']=$response->count();
            $result['data'] =$response->paginate(25);
            return $result;
        } catch (\Exception $exception) {
            //todo:日志 错误

        }
    }
    /**
     *  $count = count($response);
    $size = 25;//加配置
    $page = empty($option['page']) ? 1 : $option['page'];
    $options = ['var_page' => 'page', 'path' => '/wavlink/product/index.html', 'query' => ''];
    $pages = Bootstrap::make($response, $size, $page, $count, false, $options);
            $result['data'] = array_slice($response,($page - 1) * $size);
            $result['page'] = $pages;
     */
}