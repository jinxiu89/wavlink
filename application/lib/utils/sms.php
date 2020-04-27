<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/3/30 18:12
 * @User: admin
 * @Current File : sms.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\lib\utils;

use think\facade\Config;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

/**
 * Class sms
 * 短信推送，验证短信接口
 *
 * @package app\lib\utils
 */
class sms
{
    /**
     * 阿里接口
     * https://api.aliyun.com/new#/?product=Dysmsapi&version=2017-05-25&api=SendSms&params={}&tab=DEMO&lang=PHP
     * @param $phone
     * @param $code
     * @return array
     * @throws ClientException
     */
    public static function ali($phone, $code)
    {
        $query = [
            'RegionId' => strval(Config::get('alicloud.sms.regionId')),
            'PhoneNumbers' => $phone,
            'SignName' => Config::get('alicloud.sms.SignName'),
            'TemplateCode' => Config::get('alicloud.sms.TemplateCode'),
            'TemplateParam' => json_encode(['code' => $code]),
        ];
        //todo::阿里短信接口
        AlibabaCloud::accessKeyClient(Config::get('alicloud.app.accessKeyId'), Config::get('alicloud.app.accessSecret'))
            ->regionId(Config::get('alicloud.sms.regionId'))->asDefaultClient();
        try {
            $result = AlibabaCloud::rpc()
                ->product(Config::get('alicloud.sms.product'))
                ->version(Config::get('alicloud.sms.version'))
                ->action(Config::get('alicloud.sms.action'))
                ->method(Config::get('alicloud.sms.methods'))
                ->host(Config::get('alicloud.sms.host'))
                ->options([
                    'query' => $query,
                ])
                ->request();
            return $result->toArray();
        } catch (ClientException $exception) {
//            return show();
        } catch (ServerException $exception) {
//            return show();
        }
    }

    public function jd()
    {
        //todo::京东短信接口
    }
}