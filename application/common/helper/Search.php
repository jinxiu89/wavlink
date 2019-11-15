<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/5/27
 * Time: 17:22
 */

namespace app\common\helper;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use think\facade\Config;

/**
 * Class Search
 * @package app\common\helper
 */
class Search
{
    /**
     * @param $model
     * @param $map
     * @param $order
     * @param int $page
     * @return array|string
     * @throws \think\exception\DbException
     */
    public static function search($model, $map, $order, $page = 12)
    {
        //公共查询函数
        $data = model($model)->where($map)->order($order)->paginate($page);
        $counts = model($model)->where($map)->count();
        if ($data) {
            $result = array(
                'data' => $data,
                'count' => $counts,
            );
            return $result;
        } else {
            return '';
        }
    }

    /**
     * @return Client
     */
    public static function createClient()
    {
        $builder=ClientBuilder::create()->setHosts(Config::get('search.client'));
        /*if(Config::get('app.debug') == true){
            //todo:: 写日志（到thinkphp本地来）
        }*/
        return $builder->build();
    }

    /**
     * @return array|callable
     */
    public static function createIndex()
    {
        $params = [
            'index' => '',
            'type' => '',
            'id' => '',
            'body' => ''
        ];
        return self::createClient()->index($params);
    }

    public static function getIndex()
    {
        $params = [
            'index' => '',
            'type' => '',
            'id' => ''
        ];
        return self::createClient()->get($params);
    }
}