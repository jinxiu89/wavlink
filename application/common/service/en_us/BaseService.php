<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/4/9 14:46
 * @User: admin
 * @Current File : BaseService.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\common\service\en_us;


use AlibabaCloud\Client\Config\Config;

/**
 * Class BaseService
 * @package app\common\service\en_us
 * 以后的缓存都在service层来做
 */
class BaseService
{
    protected $model;
    protected $debug;

    public function __construct()
    {
        $this->debug = Config::get('app_debug');
    }
}