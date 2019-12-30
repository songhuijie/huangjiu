<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/30
 * Time: 11:25
 */
namespace App\Services;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use App\Model\Config;

class AlibabaSms{

    const SING_NAME = '洋烈特产商城';
    const TEMPLATE_CODE = 'SMS_181555580';
    /**
     * 获取sms Key
     * @return array
     */
    public static function getSmsKey(){
        $config = new Config();
        $config = $config->getConfig();
        $sms_key = $config->sms_key;
        $sms_secret = $config->sms_secret;
        $data = [
            'sms_key'=>$sms_key,
            'sms_secret'=>$sms_secret,
        ];
        return $data;
    }

    /**
     * 初始化 阿里云
     * @return \AlibabaCloud\Client\Clients\AccessKeyClient
     * @throws ClientException
     */
    public function init(){
        $keys = self::getSmsKey();
        AlibabaCloud::accessKeyClient($keys['sms_key'], $keys['sms_secret'])
            ->regionId('cn-hangzhou')
            ->asDefaultClient();
    }

    /**
     * 单条发送短信
     * @param $phoneNumber
     * @param $SignName
     * @param $TemplateCode
     * @param $TemplateParam  json格式
     * @throws ClientException
     */
    public function SendSms($phoneNumber,$SignName,$TemplateCode){
        self::init();

        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => "cn-hangzhou",
                        'PhoneNumbers' =>$phoneNumber,
                        'SignName' =>$SignName,
                        'TemplateCode' =>$TemplateCode,
//                        'TemplateParam' =>$TemplateCode,//验证码或者 特定字段时候传
                    ],
                ])
                ->request();
            print_r($result->toArray());
        } catch (ClientException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        }
    }


}