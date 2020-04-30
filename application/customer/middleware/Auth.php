<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/4/8 17:05
 * @User: admin
 * @Current File : Auth.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\customer\middleware;

use think\facade\Request;
use think\facade\Session;
use think\response\Redirect;

/**
 * Class Auth
 * @package app\customer\middleware
 */
class Auth
{
    /**
     * @param $request
     * @param \Closure $next
     * @return mixed|Redirect
     */
    public function handle($request, \Closure $next)
    {
        $next_jump = Request::header('Referer') ? Request::header('Referer') : url('customer_info');
        if (!Session::get('CustomerInfo', 'Customer')) {
            return \redirect(url('customer_login',['next'=>$next_jump]));
        }
        return $next($request);
    }
}