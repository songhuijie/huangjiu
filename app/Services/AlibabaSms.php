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

    const SING_NAME = '巴人谷食品';
    const TEMPLATE_CODE = 'SMS_181555580';//快递发送短信 模板
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
     * @throws ClientException
     */
    public static function init(){
        $keys = self::getSmsKey();
        AlibabaCloud::accessKeyClient($keys['sms_key'], $keys['sms_secret'])
            ->regionId('cn-hangzhou')
            ->asDefaultClient();
    }

    /**
     * 单条发送短信
     * @param $phoneNumber
     * @param null $SignName
     * @param null $TemplateCode
     * @return array|bool
     * @throws ClientException
     */
    public static function SendSms($phoneNumber,$SignName = null,$TemplateCode = null){

        if($SignName == null){
            $SignName = self::SING_NAME;
        }
        if($TemplateCode == null){
            $TemplateCode = self::TEMPLATE_CODE;
        }
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
                        'PhoneNumbers' => "$phoneNumber",
                        'SignName' => "$SignName",
                        'TemplateCode' => "$TemplateCode",
                    ],
                ])
                ->request();

            return $result->toArray();
        } catch (ClientException $e) {
            return false;
        } catch (ServerException $e) {
            return false;
        }
    }


}