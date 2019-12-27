<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/27
 * Time: 11:10
 */
namespace App\Services;


use App\Model\Config;

class CourierBirdService{


    const REQ_URL = 'http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx';
    const EBUSINESS_ID = '1425747';
    //调用查询物流轨迹
    //---------------------------------------------

    //顺丰速运	SF
    //百世快递	HTKY
    //中通快递	ZTO
    //申通快递	STO
    //圆通速递	YTO
    //韵达速递	YD
    //邮政快递包裹	YZPY
    //EMS	EMS
    //天天快递	HHTT
    //京东快递	JD
    //优速快递	UC
    //德邦快递	DBL
    //宅急送	ZJS
    const EXPRESS_TYPE = [
        1=>'SF',
        2=>'HTKY',
        3=>'ZTO',
        4=>'STO',
        5=>'YTO',
        6=>'YD',
        7=>'YZPY',
        8=>'EMS',
        9=>'HHTT',
        10=>'JD',
        11=>'UC',
        12=>'DBL',
        13=>'ZJS',
    ];
    //---------------------------------------------
    public static function getConfig(){
        $configs = new Config();
        $config = $configs->getConfig();
        if($config){
            $data['express_key'] = $config->express_key;
            $data['EBusinessID'] = $config->EBusinessID;
        }else{
            $data['express_key'] = '';
            $data['EBusinessID'] = '';
        }


        return $data;
    }

    /**
     * Json方式 查询订单物流轨迹
     * @param $Order_Code
     * @param $type
     * @return url响应返回的html
     */
    public static function  getOrderTracesByJson($Order_Code,$type){

        $config = self::getConfig();
//        $requestData= "{'OrderCode':$Order_Code}";
        $express_type = self::EXPRESS_TYPE[$type];
        $requestData= "{'OrderCode':'','ShipperCode':'$express_type','LogisticCode':'$Order_Code'}";


        $datas = array(
            'EBusinessID' => $config['EBusinessID'],
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = self::encrypt($requestData, $config['express_key']);
        $result=self::sendPost(self::REQ_URL, $datas);

        //根据公司业务处理返回的信息......

        return json_decode($result,true);
    }

    /**
     *  post提交数据
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据
     * @return url响应返回的html
     */
    public static function sendPost($url, $datas) {
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if(empty($url_info['port']))
        {
            $url_info['port']=80;
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }
        fclose($fd);

        return $gets;
    }

    /**
     * 电商Sign签名生成
     * @param data 内容
     * @param appkey Appkey
     * @return DataSign签名
     */
    public static function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }

}