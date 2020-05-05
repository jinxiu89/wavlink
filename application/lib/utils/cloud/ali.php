<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/4/20 16:52
 * @User: admin
 * @Current File : upload.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\lib\utils\cloud;

use OSS\Model\ObjectListInfo;
use OSS\OssClient;
use OSS\Core\OssException;
use OSS\Core\OssUtil;
use think\facade\Config;

/**
 * Class upload
 * @package app\lib\utils
 */
class ali
{
    /***
     * @return OssClient
     */
    public static function createClient()
    {
        $accessKeyID = Config::get('alicloud.app.accessKeyId');
        $accessKeySecret = Config::get('alicloud.app.accessSecret');
        $endpoint = Config::get('alicloud.oss.endpoint');
        try {
            return new OssClient($accessKeyID, $accessKeySecret, $endpoint);
        } catch (OssException $ossException) {
            //todo: 抛出异常
        }
    }

    /***
     * 列出 桶里的数据
     * 后面使用的时候再来丰富注释和功能 列出桶里的文件，参数说明：
     * 1、默认桶为wavlink通，我们wavlink 的所有资料都放在wavlink里
     * 2、prefix参数，是指列出桶里的哪个文件夹里的文件，默认拿images文件夹
     * 3、如果我们在视频的功能里列出文件就应该拿videos文件夹里的文件
     * @param string $bucket
     * @param string $prefix
     * @return ObjectListInfo|string
     */
    public static function listObj($bucket = 'wavlink', $prefix = 'images')
    {
        $nextMarker = '';
        $options = ['delimiter' => '/', 'marker' => $nextMarker, 'prefix' => $prefix];
        try {
            $listObjectInfo = self::createClient()->listObjects($bucket, $options);
        } catch (OssException $e) {
            //todo:异常还没处理
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        $objectList = $listObjectInfo->getObjectList(); // object list
        $prefixList = $listObjectInfo->getPrefixList();
        $items = [];
        if (!empty($prefixList)) {
            foreach ($prefixList as $prefixInfo) {
                $items[] = $prefixInfo->getPrefix();
            }
        }
        if (!empty($objectList)) {
            foreach ($objectList as $objectInfo) {
                if ($objectInfo->getSize() != 0) {
                    $tem['size'] = $objectInfo->getSize();
                    $tem['key'] = $objectInfo->getKey();
                    $tem['type'] = $objectInfo->getType();
                    $tem['last_modified'] = $objectInfo->getLastModified();

                    $items[] = $tem;
                }
            }
        }
        return $items;
    }

    /**
     * @param string $bucket 桶，默认为wavlink桶
     * @param string $key 文件名
     * @param 实体文件（也可以是路径，一般上传时就指定到） $file
     * @return bool
     */
    public static function putFile($bucket='wavlink',$key = '', $file)
    {
        try {
            if (self::createClient()->uploadFile($bucket, $key, $file)) return true;
            return false;
        } catch (OssException $exception) {
            //todo::待解决异常问题
            return false;
        }
    }

    /**
     * @param $bucket
     * @param $object
     * @return bool
     */
    public function delFile($bucket, $object)
    {
        try {
            if (self::createClient()->deleteObject($bucket, $object)) return true;
            return false;//不明原因失败
        } catch (OssException $exception) {
            //todo::阿里云那边的异常在此不做
            return false;
        }
    }

}