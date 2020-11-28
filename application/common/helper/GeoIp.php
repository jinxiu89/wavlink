<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/11/28 10:40
 * @User: kevin
 * @Current File : GeoIp.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\common\helper;

use GeoIp2\Database\Reader;

/**
 * Class GeoIp
 * @package app\common\helper
 */
class GeoIp
{
    protected $reader;

    public function __construct()
    {
        $this->reader = new Reader(PUBLIC_PATH . '/GeoLite2-City.mmdb');
    }

    /**
     * @param $ip
     * @return mixed
     */
    public function getCode($ip)
    {
        try{
            $record=$this->reader->city($ip);
            return $record->country->isoCode;
        }catch (\Exception $exception){
            return $exception->getMessage();
        }
    }
}