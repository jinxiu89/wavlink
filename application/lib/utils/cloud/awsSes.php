<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/12/10 9:32
 * @User: kevin
 * @Current File : awsSes.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\lib\utils\cloud;

use Aws\Ses\Exception;
use Aws\Ses\SesClient;
use think\facade\Config;

/**
 * Class awsSes
 * @package app\lib\utils\cloud
 */
class awsSes
{
    protected $client;

    public function __construct()
    {//todo::初始化参数
        $conf = Config::get('awsCloud.Ses');
        $options = [
            'version' => $conf['version'],
            'region' => $conf['region'],
            'credentials' => [
                'key' => $conf['accessKeyId'],
                'secret' => $conf['secretAccessKey'],
            ],
            'debug' => false,
        ];
        $this->client = new SesClient($options);
        unset($options);
    }

    /**
     * @param array $template_data 传递过来的数据
     * @param string $template 是用英文模板还是中文模板
     * @param string $receiver 发送到谁哪里去
     */
    public function sendTemplateEmail(array $template_data, $template = 'verificationCode_cn', $receiver = "jinxiu89@163.com")
    {
        $conf = Config::get('awsCloud.Smtp');
        $sendParams = [
            'Source' => "Do Not Reply<" . $conf['sender'] . ">", //从哪里发
            'Template' => $template, //模板
            "Destination" => [
                "ToAddresses" => [$receiver],//发送到哪里
            ],
            "TemplateData" => json_encode($template_data),//json 数据传给模板发送API
        ];
        try {
            return $this->client->sendTemplatedEmail($sendParams);

        } catch (Exception\SesException $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * 前端调用此方法发送验证码邮件
     * @param string $code 验证码
     * @param string $type 注册还是其他操作
     * @param string $language 操作模板
     * @param string $email 发送到哪个邮箱
     * @param string $expired 验证码超时时间
     */
    public function SendCustomVerificationEmail($code = '', $type = 'signup', $language = 'cn', $email = 'jinxiu89@163.com', $expired = "10")
    {
        $template_data = [
            'service_name' => "WAVLINK",
            'email' => $email,
            'type' => $type, //是注册还是
            'verification_code' => $code,
            'expired_time' => $expired
        ];
        $template = 'verificationCode_' . $language;
        return $this->sendTemplateEmail($template_data, $template, $receiver = $email);
    }

    public function sendUrVerificationEmail()
    {
        //todo::发送激活链接邮件
    }

}