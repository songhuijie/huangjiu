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

    public $tenpalate_id = 'GOk3_dOesStpuIxF6_xTIKjkjQ8HY25GYr9ZukiDoi8';
    public $uel = '/pages/index/index';
    const TENPALATE_ID = 'GOk3_dOesStpuIxF6_xTIKjkjQ8HY25GYr9ZukiDoi8';
    const UEL = '/pages/index/index';

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
     */
    public static function send_notice(){

        $access_token_array = self::getAccessToken();
        //获取access_token
        $access_token = Redis::get('access_token');
        if ($access_token){
            $access_token2=$access_token;
        }else{
            $appid = $access_token_array['appid'];
            $secret = $access_token_array['secret'];
            $json_token=self::curl_post("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret");
            $access_token1=json_decode($json_token,true);
            Log::channel('wechat')->info('appid:'.$appid);
            Log::channel('wechat')->info('secret:'.$secret);
            Log::channel('wechat')->info($json_token);
            $access_token2=$access_token1['access_token'];
            Redis::setex('access_token',1800,$access_token2);
        }
        //模板消息
        $json_template = self::json_tempalte();
        $url="https://api.weixin.qq.com/cgi- bin/message/template/send?access_token=".$access_token2;
        $res=self::curl_post($url,urldecode($json_template));
        if ($res['errcode']==0){
            return '发送成功';
        }else{
            return '发送失败';
        }
    }


    /**
     * 将模板消息json格式化
     */
    public static function json_tempalte(){
        //模板消息
        $template=array(
            'touser'=>'.$openid.',  //用户openid
            'template_id'=>self::TENPALATE_ID, //在公众号下配置的模板id
            'url'=>self::UEL, //点击模板消息会跳转的链接
            'topcolor'=>"#7B68EE",
            'data'=>array(
                'first'=>array('value'=>urlencode("您的活动已通过"),'color'=>"#FF0000"),
                'keyword1'=>array('value'=>urlencode('测试文章标题'),'color'=>'#FF0000'),  //keyword需要与配置的模板消息对应
                'keyword2'=>array('value'=>urlencode(date("Y-m-d H:i:s")),'color'=>'#FF0000'),
                'keyword3'=>array('value'=>urlencode('测试发布人'),'color'=>'#FF0000'),
                'remark' =>array('value'=>urlencode('备注：这是测试'),'color'=>'#FF0000'), )
        );
        $json_template=json_encode($template);
        return $json_template;
    }

    public static function curl_post($url , $data=array()){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        // POST数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // 把post的变量加上
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}
