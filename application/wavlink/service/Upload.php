<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/5/5 9:49
 * @User: admin
 * @Current File : Upload.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\wavlink\service;

use think\facade\Config;
use app\lib\utils\cloud\ali;

/**
 * Class Upload
 * @package app\wavlink\service
 */
class Upload extends Base
{
    /**
     * @param string $key
     * @param $file
     * @return bool
     */
    public function aliUpload($key = '', $file)
    {
        $this->bucket = Config::get('alicloud.oss.bucket');
        if(ali::putFile($this->bucket, $key, $file)) return true;
        return false;
    }
}