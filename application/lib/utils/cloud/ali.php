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
use OSS\OssClient;
use OSS\Core\OssException;
use OSS\Core\OssUtil;
/**
 * Class upload
 * @package app\lib\utils
 */
class ali
{
    /***
     * @return OssClient
     */
    public static function createClient(){
        $accessKeyID='';
        $accessKeySecret='';
        $endpoint='';
        try{
            return new OssClient($accessKeyID,$accessKeySecret,$endpoint);
        }catch (OssException $ossException){
            //todo: 抛出异常
        }
    }
    public function putFile(){

    }

}