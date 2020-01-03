<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/3
 * Time: 11:40
 */
namespace App\Services;

use App\Model\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class WePushService{

    const TENPALATE_ID = 'JMVOF2RNdXR6_rtxCthQ8TlOhYqYnap4sv1e_LvRUIo';
    const UEL = '/pages/index/index';
    const OPENID = 'oBNBp5LEwAZiU4IcCXlMbF4MLeS8';

    const TENPALATE_TYPE = [
        1=>'_qN8WDbPIumSlNoLAqkzuZTbgbLPXqjye8--09eoOOk',//订单发货提醒
//        2=>'U5yQ29gVqpeiWZFpuyI26O1CwfR1y4IJ-jrTR1-E-ZQ',//好友下单成功通知
        2=>'dYtAugpKNoXx-DqGihyK1jIXr45ds27SbLUKkxQF1lQ',//订单派送成功通知
        3=>'5UOEEFf0Flbc9fpdEGH0tIQVP8pVRqcEBf41gCRBnMY',//代理提醒
    ];

    public static function getAccessToken(){
        $config = new Config();
        $config = $config->getConfig();
        $config_array = [
            'appid'=>$config->appid,
            'secret'=>$config->secret,
        ];
        return $config_array;
    }

    /**
     * 发送模板消息
     * @param $type
     * @return string
     */
    public static function send_notice($type){

        $access_token_array = self::getAccessToken();
        //获取access_token
        $access_token = Redis::get('access_token');
        if ($access_token){
            Log::channel('wechat')->info('redis调用');
            $access_token2=$access_token;
        }else{
            $appid = $access_token_array['appid'];
            $secret = $access_token_array['secret'];


            $json_token=self::curl_post("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret");
            $access_token1=json_decode($json_token,true);
            Log::channel('wechat')->info('创建');
            Log::channel('wechat')->info($json_token);
            $access_token2=$access_token1['access_token'];
            Redis::setex('access_token',7200,$access_token2);
        }

        $json_template = self::json_tempalte($type);
        //模板消息

        $url="https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=".$access_token2;
        $res=self::curl_post($url,urldecode($json_template));

//     $url="https://api.weixin.qq.com/wxaapi/newtmpl/gettemplate?access_token=".$access_token2;
//     $res = self::curl_get($url);


        Log::channel('wechat')->info('模板推送返回结果');
        Log::channel('wechat')->info($res);


        $res = json_decode($res,true);
        dump($res);
        if ($res['errcode']==0){
            return '发送成功';
        }else{
            return '发送失败';
        }
    }


    /**
     * 获取模板列表
     */
    public static function getTemplateList(){

        $template = [
            "offset"=> 0,
            "count"=> 0,
        ];
        $json_template=json_encode($template);
        return $json_template;
    }

    /**
     * 将模板消息json格式化
     * @param $type
     * @return string
     */
    public static function json_tempalte($type,$open_id,$message_data){
        //模板消息
        $toUser = $open_id;
        $template_id = isset(self::TENPALATE_TYPE[$type])?self::TENPALATE_TYPE[$type]:self::TENPALATE_TYPE[1];
        $url = self::UEL;

        switch ($type){
            case 1:
                $data =[
                    'character_string1'=>['value'=>$message_data['character_string1']],//订单编号
                    'thing2'=>$message_data['thing2'],//商品名称
                    'thing6'=>$message_data['thing6'],//物流公司
                    'character_string7'=>$message_data['character_string7'],//物流单号
                    'phrase4'=>$message_data['phrase4'],//发货状态
                ];
                break;
            case 2:
                break;
            case 3:
                break;
            case 4:
                break;
            default:
                break;
        }

        $template=array(
            'touser'=>$toUser,  //用户openid
            'template_id'=>$template_id, //在公众号下配置的模板id
            'url'=>$url, //点击模板消息会跳转的链接
            'topcolor'=>"#7B68EE",
            'data'=>$data,
            'data'=>array(

//                'character_string1'=>array('value'=>urlencode("123456"),'color'=>"#FF0000"),
//                'thing2'=>array('value'=>urlencode('黄酒'),'color'=>'#FF0000'),  //keyword需要与配置的模板消息对应
//                'thing6'=>array('value'=>urlencode(date("申通")),'color'=>'#FF0000'),
//                'character_string7'=>array('value'=>urlencode('123'),'color'=>'#FF0000'),
//                'phrase4' =>array('value'=>urlencode('待发货'),'color'=>'#FF0000')

                'thing1'=>array('value'=>urlencode("huangjiu"),'color'=>"#FF0000"),
                'thing2'=>array('value'=>urlencode("huangjiu"),'color'=>"#FF0000"),

            )


        );

        $json_template=json_encode($template);
        dump($template);
        Log::channel('wechat')->info($json_template);
        return $json_template;
    }

    /**
     * get 请求
     * @param $url
     * @return mixed
     */
    public static function curl_get($url){

        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上

        $output = curl_exec($ch);//运行curl
        curl_close($ch);
        return $output;
    }

    public static function curl_post($url , $data=array()){


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // 以文件流形式返回
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if (!empty($data))
        {
            // POST请求
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        $output = curl_exec($ch);
        curl_close($ch);

        // 返回
        return $output;
    }
}
