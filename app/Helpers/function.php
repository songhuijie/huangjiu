<?php
/**
 * 设置中国时区
 */
date_default_timezone_set('PRC');
/**
 * Created by PhpStorm.
 * User: lord
 * Date: 2018/8/15
 * Time: 下午6:34
 */

/**
 * 计算几分钟前、几小时前、几天前、几月前、几年前。
 * $agoTime string Unix时间
 * @author tangxinzhuan
 * @version 2016-10-28
 */
function time_ago($agoTime)
{
    $agoTime = (int)$agoTime;

    // 计算出当前日期时间到之前的日期时间的毫秒数，以便进行下一步的计算
    $time = time() - $agoTime;

    if ($time >= 31104000) { // N年前
        $num = (int)($time / 31104000);
        return $num.'年前';
    }
    if ($time >= 2592000) { // N月前
        $num = (int)($time / 2592000);
        return $num.'月前';
    }
    if ($time >= 86400) { // N天前
        $num = (int)($time / 86400);
        return $num.'天前';
    }
    if ($time >= 3600) { // N小时前
        $num = (int)($time / 3600);
        return $num.'小时前';
    }
    if ($time > 60) { // N分钟前
        $num = (int)($time / 60);
        return $num.'分钟前';
    }
    return '1分钟前';
}

function startEndTime(){
    /**
     * 获取今日开始时间戳和结束时间戳
     */
    $beginToday = mktime(0,0,0,date('m'),date('d'),date('Y'));
    $endToday = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
    /**
     * 获取昨日起始时间戳和结束时间戳
     */
    $beginYesterday = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
    $endYesterday = mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
    /**
     * 获取本周起始时间
     */
    $beginWeek = mktime(0,0,0,date("m"),date("d")-date("w")+1,date("Y"));
    $endWeek = mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y"));

    /**
     * 获取上周起始时间戳和结束时间戳
     */
    $beginLastweek = mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
    $endLastweek = mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
    /**
     * 获取本月起始时间戳和结束时间戳
     */
    $beginThismonth = mktime(0,0,0,date('m'),1,date('Y'));
    $endThismonth = mktime(23,59,59,date('m'),date('t'),date('Y'));

    return ['beginToday'=>$beginToday,"endToday"=>$endToday,'beginYesterday'=>$beginYesterday,'endYesterday'=>$endYesterday,
        'beginWeek'=>$beginWeek,'endWeek'=>$endWeek,'beginLastweek'=>$beginLastweek,'endLastweek'=>$endLastweek,
        'beginThismonth'=>$beginThismonth,'endThismonth'=>$endThismonth];
}

/**
 * 計算多長時間后的時間
 */
function howLongTime(){
    /**今天*/
    $today = date("Y-m-d",time());
    /**昨天*/
    $yesterday = date("Y-m-d",strtotime("-1 day"));
    /**明天*/
    $tomorrow = date("Y-m-d",strtotime("+1 day"));
    /**一周后*/
    $week = date("Y-m-d",strtotime("+1 week"));
    /**一周零两天四小时两秒后*/
//    date("Y-m-d G:H:s",strtotime("+1 week 2 days 4 hours 2 seconds"))
    /**下个星期四*/
//    date("Y-m-d",strtotime("next Thursday"))
    /**上个周一*/
//    date("Y-m-d",strtotime("last Monday"))
    /**一個月前*/
    $lastMonth = date("Y-m-d",strtotime("last month"));
    /**一個月后*/
    $month = date("Y-m-d",strtotime("+1 month"));
    /**一年後*/
    /**十年後*/
    $tenYear = date("Y-m-d",strtotime("+10 year"));

}

/**
 * 返回值
 */
function ReCode($code,$msg,$data,$count=0){
    return ['code'=>$code,'msg'=>$msg,'data'=>$data,'count'=>$count];
}

function __childtree($father,$son,$level=0){
    $data = array();
    foreach ($son as $k => $v) {
        if(intval($v['pathid'])==intval($father['id'])){
            array_push($data,$v);
            $father['childen'] = $data;
            unset($son[$k]);
        }
    }
    return $father;
}
function childtree($son,$alone=false){//
    $father=array();
    foreach ($son as $key => $val) {
        if(intval($val['pathid'])==0){//获取第一代
            $val['childen'] = [];
            array_push($father, $val);
            unset($son[$key]);
        }
    }
    foreach ($father as $key => $val) {
        $father[$key]=__childtree($val,$son,0);
    }
    if($alone){//保留没有父级的孤儿
        foreach ($son as $key => $val) {
            if(__childtreeOut($father,$val['pathid'])!=true) {
                array_push($father, $val);
            }
        }
    }
    return $father;
}
//去除有父级元素
function __childtreeOut($data,$pid){
    foreach ($data as $k => $v) {
        if ($v['pid']==0) {
            if($v['id']!=$pid){
                return true;
            }
        }
    }

}
/**
 * @return array|false|mixed|string
 * u获取ip地址
 */
function getIp(){
    $onlineip='';
    if(getenv('HTTP_CLIENT_IP')&&strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')){
        $onlineip=getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR')&&strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown')){
        $onlineip=getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR')&&strcasecmp(getenv('REMOTE_ADDR'),'unknown')){
        $onlineip=getenv('REMOTE_ADDR');
    } elseif(isset($_SERVER['REMOTE_ADDR'])&&$_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],'unknown')){
        $onlineip=$_SERVER['REMOTE_ADDR'];
    }
    return $onlineip;
}

/**
 * @param $file
 * @param $path
 * @return string
 * 图片上传  layui 上传
 */
function upload($file,$file_path){
    // 判断文件是否上传
    if ($file) {
        // 获取后缀名
        $ext=$file->getClientOriginalExtension();
        // 新的文件名
        $newFile=date('YmdHis',time()).'_'.rand().".".$ext;
        // 上传文件操作
        $url = nowUrl().'/'.$file_path.date("Ymd",time())."/".$newFile;
        $path = public_path().'/'.$file_path.date("Ymd",time());
        if (!is_dir($path)) {
            mkdir($path,0777,true);
        }
        if($file->move($path,$newFile)){
            return json_encode(['code'=>0,'msg'=>'图片信息','data'=>['src'=>$url]]);
        }
    }
}
/**
 * @param $file
 * @param $path
 * @return string
 * 图片上传 easyweb版上传
 */
function easywebUpload($file,$file_path){
    // 判断文件是否上传
    if ($file) {
        // 获取后缀名
        $ext=$file->getClientOriginalExtension();
        // 新的文件名
        $newFile=date('YmdHis',time()).'_'.rand().".".$ext;
        // 上传文件操作
        $url = nowUrl().'/'.$file_path.date("Ymd",time())."/".$newFile;
        $path = public_path().'/'.$file_path.date("Ymd",time());
        if (!is_dir($path)) {
            mkdir($path,0777,true);
        }
        if($file->move($path,$newFile)){
            return json_encode(['uploaded'=>1,'fileName'=>'图片信息','url'=>$url]);
        }else{
            return json_encode(['uploaded'=>0,'error'=>'图片上传失败']);
        }
    }
}
// obj 转array  json_decode 无效时使用
function object_array($array) {
    if(is_object($array)) {
        $array = (array)$array;
    } if(is_array($array)) {
        foreach($array as $key=>$value) {
            $array[$key] = object_array($value);
        }
    }
    return $array;
}
/**
 * @param $url
 * @return mixed
 * curl模拟get请求
 */
function curlGet($url){
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
    $tmpInfo = curl_exec($curl);     //返回api的json对象
    //关闭URL请求
    curl_close($curl);
    return $tmpInfo;    //返回json对象
}

function curlInfoPost($url){
    $postUrl = $url;
    $curlPost = '';
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch);//运行curl
    curl_close($ch);

    $j=json_decode($data);
    return $j;
}


/**
 * @param $url
 * @param $xml
 * @return mixed
 * curl 模拟post请求
 */
function curlPost($url, $xml)
{
    $ch = curl_init();
    //设置抓取的url
    curl_setopt($ch, CURLOPT_URL, $url);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //设置post方式提交
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

    $tmpInfo = curl_exec($ch);

    //返回api的json对象
    //关闭URL请求
    curl_close($ch);
    return $tmpInfo;    //返回json对象
}

/**
 * @param $address
 * @return string
 * 腾讯地图
 * 通过地址解析经纬度
 */
function tencentMap_address($key,$address){
    $url = "https://apis.map.qq.com/ws/geocoder/v1/?address={$address}&key={$key}";
    $data = curlGet($url);
    $result = json_decode($data,true);
    if(isset($result['result'])){
        return ['code'=>0,'msg'=>'获取成功','data'=>$result['result']['location']];
    }else{
        return ['code'=>1,'msg'=>'获取失败','data'=>''];
    }
}

/**
 * @param $key
 * @param $location
 * @return string
 * 通过经纬度返回地址
 */
function tencentMap_location($key,$location){
    $url = "https://apis.map.qq.com/ws/geocoder/v1/?location={$location}&key={$key} ";
    $data = curlGet($url);
    if($data){
        $result = json_decode($data,true);
        return $result['result']['ad_info'];
    }else{
        return '获取失败!';
    }
}

/**
 * @return bool
 * 主动判断是否HTTPS
 */
function isHTTPS()
{
    if (defined('HTTPS') && HTTPS) return true;
    if (!isset($_SERVER)) return FALSE;
    if (!isset($_SERVER['HTTPS'])) return FALSE;
    if ($_SERVER['HTTPS'] === 1) {  //Apache
        return TRUE;
    } elseif ($_SERVER['HTTPS'] === 'on') { //IIS
        return TRUE;
    } elseif ($_SERVER['SERVER_PORT'] == 443) { //其他
        return TRUE;
    }
    return FALSE;
}
// 返回开始时间到结束时间的天数
function sumTime($startdate,$enddate){
    $time = '';
    $date=floor((strtotime($enddate)-strtotime($startdate))/86400);
    if($date>0){
        $time .=$date."天";
    }
    $hour=floor((strtotime($enddate)-strtotime($startdate))%86400/3600);
    if($hour>0){
        $time .=$hour.'时';
    }
    $minute=floor((strtotime($enddate)-strtotime($startdate))%86400%3600/60);
    if($minute>0){
        $time .=$minute.'分';
    }
    return $time;
}
/**
 * 返回当前域名
 */
function nowUrl(){
    $host = (isHTTPS() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST']; //获取域名
    return $host;
}
/**
 * @param $appid
 * @param $secret
 * @return mixed
 * 小程序获取accesstoken
 */
function accessToken($appid,$secret){
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}&grant_type=client_credential";
    $result = curlGet($url);
    $data = json_decode($result,true);
    return $data['access_token'];
}
/**
 * @param $filePath
 * @param $smallPath
 * @return string
 * 生成小程序二维码
 */
function QRcode($appid,$secret,$filePath,$smallPath,$scene,$accesstoken){
    $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$accesstoken;
    $data = [
        'scene' =>$scene,
        'page'=>$smallPath,
        'width' =>"200"
    ];
    $result = curlPost($url,json_encode($data));
    $filename = date('YmdHis',time())."_".rand().".png";
    file_put_contents($filePath.$filename,$result,true);
    return $filename;
}
/**
生成透明小程序码
 */
function smallQrcode($appid,$secret,$filePath,$smallPath){
    $url = "https://api.weixin.qq.com/wxa/getwxacode?access_token=".accesstoken($appid,$secret);
    $data = [
        'page'=>$smallPath,
        'width' => "200",
        'is_hyaline'=>true,
    ];
    $result = curlPost($url,json_encode($data));
    $filename = date('YmdHis',time())."_".rand().".png";
    file_put_contents($filePath.$filename,$result,true);
    return $filename;
}
/**
 * 生成宣传海报
 * @param array  参数,包括图片和文字
 * @param string  $filename 生成海报文件名,不传此参数则不生成文件,直接输出图片
 * @return [type] [description]
 */
function createPoster($config=array(),$filename=""){
    //如果要看报什么错，可以先注释调这个header
    if(empty($filename)) header("content-type: image/png");
    $imageDefault = array(
        'left'=>0,
        'top'=>0,
        'right'=>0,
        'bottom'=>0,
        'width'=>100,
        'height'=>100,
        'opacity'=>100
    );
    $textDefault = array(
        'text'=>'',
        'left'=>0,
        'top'=>0,
        'fontSize'=>32,       //字号
        'fontColor'=>'255,255,255', //字体颜色
        'angle'=>0,
    );
    $background = $config['background'];//海报最底层得背景
    //背景方法
    $backgroundInfo = getimagesize($background);
    $backgroundFun = 'imagecreatefrom'.image_type_to_extension($backgroundInfo[2], false);
    $background = $backgroundFun($background);
    $backgroundWidth = imagesx($background);  //背景宽度
    $backgroundHeight = imagesy($background);  //背景高度
    $imageRes = imageCreatetruecolor($backgroundWidth,$backgroundHeight);
    $color = imagecolorallocate($imageRes, 0, 0, 0);
    imagefill($imageRes, 0, 0, $color);
    // imageColorTransparent($imageRes, $color);  //颜色透明
    imagecopyresampled($imageRes,$background,0,0,0,0,imagesx($background),imagesy($background),imagesx($background),imagesy($background));
    //处理了图片
    if(!empty($config['image'])){
        foreach ($config['image'] as $key => $val) {
            $val = array_merge($imageDefault,$val);
            $info = getimagesize($val['url']);
            $function = 'imagecreatefrom'.image_type_to_extension($info[2], false);
            if($val['stream']){   //如果传的是字符串图像流
                $info = getimagesizefromstring($val['url']);
                $function = 'imagecreatefromstring';
            }
            $res = $function($val['url']);
            $resWidth = $info[0];
            $resHeight = $info[1];
            //建立画板 ，缩放图片至指定尺寸
            $canvas=imagecreatetruecolor($val['width'], $val['height']);
            imagefill($canvas, 0, 0, $color);
            //关键函数，参数（目标资源，源，目标资源的开始坐标x,y, 源资源的开始坐标x,y,目标资源的宽高w,h,源资源的宽高w,h）
            imagecopyresampled($canvas, $res, 0, 0, 0, 0, $val['width'], $val['height'],$resWidth,$resHeight);
            $val['left'] = $val['left']<0?$backgroundWidth- abs($val['left']) - $val['width']:$val['left'];
            $val['top'] = $val['top']<0?$backgroundHeight- abs($val['top']) - $val['height']:$val['top'];
            //放置图像
            imagecopymerge($imageRes,$canvas, $val['left'],$val['top'],$val['right'],$val['bottom'],$val['width'],$val['height'],$val['opacity']);//左，上，右，下，宽度，高度，透明度
        }
    }
    //处理文字
    if(!empty($config['text'])){
        foreach ($config['text'] as $key => $val) {
            $val = array_merge($textDefault,$val);
            list($R,$G,$B) = explode(',', $val['fontColor']);
            $fontColor = imagecolorallocate($imageRes, $R, $G, $B);
            $val['left'] = $val['left']<0?$backgroundWidth- abs($val['left']):$val['left'];
            $val['top'] = $val['top']<0?$backgroundHeight- abs($val['top']):$val['top'];
            imagettftext($imageRes,$val['fontSize'],$val['angle'],$val['left'],$val['top'],$fontColor,$val['fontPath'],$val['text']);
        }
    }
    //生成图片
    if(!empty($filename)){
        $res = imagejpeg ($imageRes,$filename,90); //保存到本地
        imagedestroy($imageRes);
        if(!$res) return false;
        return $filename;
    }else{
        imagejpeg ($imageRes);     //在浏览器上显示
        imagedestroy($imageRes);
    }
}
/**
 * @param $appid
 * @param $secret
 * @param $code
 * @return mixed
 * 通过公众号的code获取openid
 */
function getWChatOpenid($appid,$secret,$code){
    $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$secret}&code={$code}&grant_type=authorization_code";
    $data = curlGet($url);
    if($data){
        $result = json_decode($data,true);
        return $result;
    }
    die();
}
// 获取用户信息
function getUserInfo($access_token,$openid){
    $url ="https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
    $data = curlGet($url);
    if($data){
        $result = json_decode($data,true);
        return $result;
    }
    die();
}
// 获取access_token  公众号获取toke
function getAccess_token($appid,$secret){
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
    $data = curlGet($url);
    if($data){
        $result = json_decode($data,true);
        return $result;
    }
    die();
}

// 获取jsapi_ticket   公众号
function getTicket($token){
    $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$token}&type=jsapi";
    $data = curlGet($url);
    if($data){
        $result = json_decode($data,true);
        return $result;
    }
    die();
}
// 获取公众config

function getWxchatConfig($ticket,$appid,$url){
    $noncestr = createNonceStr(); //随机字符串
    $timeStamp = '' . time() . '';
    $data = [
        'noncestr' => $noncestr, //随机字符串,
        'jsapi_ticket' => $ticket,
        'timestamp' => $timeStamp,
        "url"=>$url,
    ];
    $data = array_filter($data);
    ksort($data);
    $str = '';
    foreach ($data as $key => $value) {
        $str .= $key . '=' . $value . '&';
    }
    $str = rtrim($str,'&');
    $data = [];
    $data = [
        'noncestr' => $noncestr, //随机字符串,
        'timestamp' => $timeStamp,
        'singtrue' => strtoupper(sha1($str)),
        'appId'=>$appid
    ];
    return $data;
}
/**
 * @param $appid
 * @param $secret
 * @param $code
 * @return mixed
 * 通过小程序的code获取openid
 */
function getOpenid($appid,$secret,$code){
    $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$code}&grant_type=authorization_code";
    $data = curlGet($url);

    if($data){
        $result = json_decode($data,true);
        $url3 = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
        $result_two_json = curlGet($url3);
        $result_two = json_decode($result_two_json,true);
        $own_data = [
            'openid'=>$result['openid'],
            'access_token'=>$result_two['access_token'],
        ];
        return $own_data;
    }
    die();
}
/**
 * @param $content
 * @param string $path
 * 写日志
 */

function writeLogs($content,$path = 'logs/logs.log'){
    $path = storage_path($path);
    $myfile = fopen($path, "a") or die("Unable to open file!");
    fwrite($myfile, $content);
    fwrite($myfile, "\r\n".date('Y-m-d H:i:s')."\r\n");
}
/**
 * api数据接口
 */
/**
 * 小程序支付
 * @param Request $request
 * @param $amountmoney 金额
 * @param $ordernumber 商户订单号
 * @return array
 */
function initiatingPayment($amountmoney, $ordernumber,$openid,$appid,$mch_id,$mer_secret,$notify_url,$body,$attach)
{
    $noncestr = createNonceStr(); //随机字符串
    $ordercode = $ordernumber;//商户订单号
    $totamount = $amountmoney;//金额
    $timeStamp = '' . time() . '';
    $data = [
        'openid' => $openid,
        'appid' => $appid,
        'mch_id' => $mch_id,
        'nonce_str' => $noncestr, //随机字符串,
        'body' => $body,
        'attach' => $attach,
        'timeStamp' => $timeStamp,
        'out_trade_no' => $ordercode,
        'total_fee' => intval($totamount * 100),
        'spbill_create_ip' => getIp(),
        'notify_url' => $notify_url,
        'trade_type' => 'JSAPI'
    ];
    //签名
    $data['sign'] = autograph($data,$mer_secret);
    $result = creatPay($data);
    $rest = xmlToArray($result);
    if(!isset($rest['prepay_id'])){
        return ReCode(3,'获取prepay_id失败',$rest);
    }
    $prepay_id = $rest['prepay_id'];
    $parameters = array(
        'appId' => $appid, //小程序ID
        'timeStamp' => $timeStamp, //时间戳
        'nonceStr' => $noncestr, //随机串
        'package' => 'prepay_id=' . $prepay_id, //数据包
        'signType' => 'MD5'//签名方式
    );
    $sign = autograph($parameters,$mer_secret);
    return ['prepay_id' => 'prepay_id=' . $prepay_id, 'timeStamp' => $timeStamp, 'noncestr' => $noncestr, 'sign' => $sign, 'sign_type' => 'MD5'];
}
// 查当前订单
/**
 *@param $appid              小程序appid
 *@param $mchid              商户号
 *@param $out_trade_no       商户订单号
 *@param $mer_secret         商户支付秘钥
 */
function queryOrder($appid,$mch_id,$mer_secret,$out_trade_no){
    $url = 'https://api.mch.weixin.qq.com/pay/orderquery';
    $data = [
        'appid' => $appid,
        'mch_id' => $mch_id,
        'nonce_str' => createNonceStr(), //随机字符串,
        'out_trade_no' => $out_trade_no,
    ];
    //签名
    $data['sign'] = autograph($data,$mer_secret);
    $xml = arrayToXml($data);
    $result = curlPost($url, $xml);
    $rest = xmlToArray($result);
    return $rest;
}
/**
 * 创建支付
 */
function creatPay($data)
{
    $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    $xml = arrayToXml($data);
    $result = curlPost($url, $xml);
//      print_r(htmlspecialchars($xml));
    //$val = $this->doPageXmlToArray($result);
    return $result;
}

// 小程序退款接口
/**
 *@param $appid              小程序appid
 *@param $mchid              商户号
 *@param $out_trade_no       商户订单号
 *@param $out_refund_no      商户退款单号
 *@param $key_pem            证书路径
 *@param $cert_pem           证书路径
 *@param $mch_secret         支付密钥
 */
function refund($appid,$mchid,$out_trade_no,$out_refund_no,$total_fee,$refund_fee,$mch_secret,$key_pem=null,$cert_pem=null){
    $data = [
        'appid' =>$appid,
        'mch_id'=> $mchid,
        'nonce_str' => createNonceStr(), //随机字符串,
        'out_trade_no' => $out_trade_no,
        'out_refund_no'=>$out_refund_no,
        'total_fee' => intval($total_fee * 100),//订单总金额
        'refund_fee'=> intval($refund_fee * 100),//退款金额
    ];
    //签名
    $data['sign'] = autograph($data,$mch_secret);
    $url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
    $xml = arrayToXml($data);
    $rest = httpCurlPost($url,$xml,$key_pem,$cert_pem);
    $result = xmlToArray($rest);
    return $result;
}


/**
 * @param $data
 * @return string
 * 生成签名
 */
function autograph($data,$mer_secret)
{
    $str = '';
    $data = array_filter($data);
    ksort($data);
    foreach ($data as $key => $value) {
        $str .= $key . '=' . $value . '&';
    }
    $str .= 'key=' . $mer_secret;
    return strtoupper(md5($str));
}

/**
 * @param int $length
 * @return string
 * 生成随机字符串
 */
function createNonceStr($length = 16)
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $str = '';
    for ($i = 0; $i < $length; $i++) {
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
}

/**
 * @param $arr
 * @return string
 * 数组转xml
 */
function arrayToXml($arr)
{
    $xml = "<xml>";
    foreach ($arr as $key => $val) {
        if (is_array($val)) {
            $xml .= "<" . $key . ">" . arrayToXml($val) . "</" . $key . ">";
        } else {
            $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
        }
    }
    $xml .= "</xml>";
    return $xml;
}

/**
 * @param $xml
 * @return mixed
 * xml转数组
 */
function xmlToArray($xml)
{
    //禁止引用外部xml实体
    libxml_disable_entity_loader(true);

    $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

    $val = json_decode(json_encode($xmlstring), true);

    return $val;
}
/**
 * 判断一个值是否存在于一个二位数组中
 */

function deep_in_array($value, $array)
{
    foreach ($array as $item) {
        if (!is_array($item)) {
            if ($item == $value) {
                return true;
            } else {
                continue;
            }
        }
        if (in_array($value, $item)) {
            return true;
        } else if (deep_in_array($value, $item)) {
            return true;
        }
    }
    return false;
}
/**
 * @param $img
 * @return string
 * 图片转base64
 */
function base64EncodeImage ($img) {
    $img_content = file_get_contents($img);
    $img_encode = base64_encode($img_content);
    $img_info = getimagesize($img);
    $img_encode= "data:{$img_info['mime']};base64,".$img_encode;
    return $img_encode;
}
/**
 * base转图片
 */
function base64_image_content($base64_image_content,$path){
    //匹配出图片的格式
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
        $type = $result[2];
        $new_file = $path."/".date('Ymd',time())."/";
        if(!file_exists($new_file)){
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($new_file, 0700);
        }
        $file_name = time().".{$type}";
        $new_file = $new_file.$file_name;
        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
            return $file_name;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

/**
 * 阿里云省份证正反面识别
 */
function IdcardDistinguish($appcode,$image,$idCardSide){
    $host = "https://ocridcard.market.alicloudapi.com";
    $path = "/idimages";
    $method = "POST";
    $headers = array();
    array_push($headers, "Authorization:APPCODE " . $appcode);
    //根据API的要求，定义相对应的Content-Type
    array_push($headers, "Content-Type".":"."application/x-www-form-urlencoded; charset=UTF-8");
    $querys = "";
    $bodys = "image={$image}"."&idCardSide={$idCardSide}"; //图片 + 正反面参数 默认正面，背面请传back
    $url = $host . $path;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_FAILONERROR, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    if (1 == strpos("$".$host, "https://"))
    {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    }
    curl_setopt($curl, CURLOPT_POSTFIELDS, $bodys);
    $tmpInfo = curl_exec($curl);     //返回api的json对象
    //关闭URL请求
    curl_close($curl);

    return $tmpInfo;    //返回json对象
}

/**
 *快递
 * $EBusinessID  快递鸟 电商id
 * $AppKey  秘钥
 * $number  快递公司编码
 * $logisticcode 物流编号
 *
 */
function  express_send($EBusinessID,$AppKey,$number,$logisticcode){
    $ReqURL = "http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx";
    /**
     * 电商id
     */
    $requestData= "{'OrderCode':'','ShipperCode':'$number','LogisticCode':'$logisticcode'}";

    $datas = array(
        'EBusinessID' => $EBusinessID,
        'RequestType' => '1002',
        'RequestData' => urlencode($requestData) ,
        'DataType' => '2-json',
    );
    $datas['DataSign'] = doWebencrypt($requestData,$AppKey);
    $result= doWebsendPost($ReqURL, $datas);

    //根据公司业务处理返回的信息......
    $res = json_decode($result,true);
    if($res['Success'] == true){
        $last_names = array_column($res['Traces'],'AcceptTime');
        array_multisort($last_names,SORT_DESC,$res['Traces']);
    }
    return $res;
}
/**
 *  post提交数据
 * @param  string $url 请求Url
 * @param  array $datas 提交的数据
 * @return url响应返回的html
 */
function  doWebsendPost($url, $datas) {
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
function  doWebencrypt($data,$appkey) {
    return urlencode(base64_encode(md5($data.$appkey)));
}
/**
 * 手机号
 */
function sms($username,$password,$content,$phone){
    $url='http://zz.shunlianweb.com';//系统接口地址
    $contents=urlencode(iconv("UTF-8", "gb2312",$content));
    $url=$url."/servlet/UserServiceAPI?method=sendSMS&extenno=&isLongSms=1&username=".$username."&password=".$password."&smstype=2&mobile={$phone}&content=".$contents;
    $html = file_get_contents($url);
    if(!strpos($html,"success")){
        return "success";
    }else{
        return "error";
    }
}
/**
 * @param $file
 * @param $path
 * @return string
 * 文件上传
 */
function cremupload($file,$file_path){
    // 判断文件是否上传
    if ($file) {
        // 获取后缀名
        $ext=$file->getClientOriginalExtension();
        $newFile = $file -> getClientOriginalName();
        // 上传文件操作
        // $url = $file_path."/".$newFile;
        $url = "cert/".$newFile;
        $path = $file_path;
        if (!is_dir($path)) {
            mkdir($path,0777,true);
        }
        if($file->move($path,$newFile)){
            return json_encode(['code'=>0,'msg'=>'信息','data'=>['src'=>$url]]);
        }
    }
}
/**
 * 退款双向证书curl
 */
function  httpCurlPost($url,$xml,$key_pem=null,$cert_pem=null){
    $ch = curl_init();
    // 设置URL和相应的选项
    curl_setopt($ch, CURLOPT_ENCODING, '');                     //设置header头中的编码类型
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);             //返回原生的（Raw）内容
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);            //禁止验证ssl证书
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);                        //header头是否设置
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSLCERTTYPE,'PEM');
    curl_setopt($ch, CURLOPT_SSLCERT, $cert_pem?$cert_pem:'cert/apiclient_cert.pem');
    curl_setopt($ch, CURLOPT_SSLKEYTYPE,'PEM');
    curl_setopt($ch, CURLOPT_SSLKEY, $key_pem?$key_pem:'cert/apiclient_key.pem');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    $tmpInfo = curl_exec($ch);
    //返回api的json对象
    //关闭URL请求
    curl_close($ch);
    return $tmpInfo;    //返回json对象
}
/**
 * 企业转账到零钱
 */
function transferAccounts($appid,$mchid,$openid,$desc,$partner_trade_no,$amount,$mch_secret,$key_pem=null,$cert_pem=null){
    $data = [
        'mch_appid' =>$appid,
        'mchid'=> $mchid,
        'openid' => $openid,
        'nonce_str' => createNonceStr(), //随机字符串,
        'desc' => $desc,
        'check_name'=>'NO_CHECK',
        'partner_trade_no' => $partner_trade_no,
        'amount' => intval($amount * 100),
        'spbill_create_ip' => getIp(),
    ];
    //签名
    $data['sign'] = autograph($data,$mch_secret);
    $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
    $xml = arrayToXml($data);
    $rest = httpCurlPost($url,$xml,$key_pem,$cert_pem);
    $result = xmlToArray($rest);
    return $result;
}
/**
 * @param $arr
 * @param $key
 * @return array
 * 二维数组根据某个字段去重
 */
function array_unset_tt($arr, $key){
    //建立一个目标数组
    $res = array();
    foreach ($arr as $value) {
        //查看有没有重复项
        if (isset($res[$value[$key]])) {
            //有：销毁
            unset($value[$key]);
        } else {
            $res[$value[$key]] = $value;
        }
    }
    return $res;
}
/**
 *计算某个经纬度的周围某段距离的正方形的四个点
 *@param lng float 经度
 *@param lat float 纬度
 *@param distance float 该点所在圆的半径，该圆与此正方形内切，默认值为0.5千米
 *@return array 正方形的四个点的经纬度坐标
 */
function point($lng,$lat,$distance=5){
    $earthdata=6371;//地球半径，平均半径为6371km
    $dlng =2 * asin(sin($distance / (2 * $earthdata)) / cos(deg2rad($lat)));
    $dlng = rad2deg($dlng);
    $dlat = $distance/$earthdata;
    $dlat = rad2deg($dlat);
    $arr=array(
        'left_top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
        'right_top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
        'left_bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
        'right_bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
    );
    return $arr;
}
/**
 * 根据经纬度算距离，返回结果单位是公里，先纬度，后经度
 * @param $lat1
 * @param $lng1
 * @param $lat2
 * @param $lng2
 * @return float|int
 */
function GetDistance($lat1, $lng1, $lat2, $lng2)
{
    $EARTH_RADIUS = 6378.137;

    $radLat1 = rad($lat1);
    $radLat2 = rad($lat2);
    $a = $radLat1 - $radLat2;
    $b = rad($lng1) - rad($lng2);
    $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
    $s = $s * $EARTH_RADIUS;
    $s = round($s * 10000) / 10000;
    $s = number_format($s, 2);
    return $s;
}
function rad($d)
{
    return $d * M_PI / 180.0;
}
/*
$array:需要排序的数组
$keys:需要根据某个key排序
$sort:倒叙还是顺序
*/
function arraySort($array,$keys,$sort='asc') {
    $newArr = $valArr = array();
    foreach ($array as $key=>$value) {
        $valArr[$key] = $value[$keys];
    }
    ($sort == 'asc') ?  asort($valArr) : arsort($valArr);//先利用keys对数组排序，目的是把目标数组的key排好序
    reset($valArr); //指针指向数组第一个值
    foreach($valArr as $key=>$value) {
        $newArr[$key] = $array[$key];
    }
    return array_values($newArr);
}
