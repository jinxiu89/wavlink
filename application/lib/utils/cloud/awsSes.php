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
use Aws\Ses\SesClient;
use Aws\Ses\Exception;
use think\facade\Config;
use think\response\Json;

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
        $options=[
            'version'=>$conf['version'],
            'region'=>$conf['region'],
            'credentials'=>[
                'key'=>$conf['accessKeyId'],
                'secret'=>$conf['secretAccessKey'],
            ],
            'debug'=>false,
        ];
        $this->client= new SesClient($options);
        unset($options);
    }

    /**
     * @param $template_data
     * @param $template
     * @param $receiver
     */
    public function sendTemplateEmail($template_data,$template='verificationCode_cn',$receiver="jinxiu89@163.com"){
        //
        $sendParams=[
            'Source'=>"Do Not Reply<noreply@wavlink.com>",
            'Template'=>$template,
            "Destination"=>[
                "ToAddresses"=> [$receiver],
            ],
//            "TemplateData"=>\json($template_data),
            "TemplateData"=>json_encode($template_data),
        ];
        try{
            $result=$this->client->sendTemplatedEmail($sendParams);
            print_r($result);

        }catch (Exception\SesException $exception){
            print_r($exception->getMessage());
        }
    }

    public function SendCustomVerificationEmail(){
        //todo::发送验证码邮件
        $template_data=[
            'service_name'=>"WAVLINK",
            'email'=>'jinxiu89@163.com',
            'type'=>'signup',
            'verification_code'=>'1382',
            'expired_time'=>"10"
        ];
        $this->sendTemplateEmail($template_data);

        /*ry{
            $this->client->sendCustomVerificationEmail([
                'EmailAddress'=>'jinxiu89@163.com',
                'TemplateName'=>'verificationCode_cn'
            ]);
        }catch (Exception\SesException $exception){

        }*/
    }
    public function sendUrVerificationEmail(){
        //todo::发送激活链接邮件
    }

}