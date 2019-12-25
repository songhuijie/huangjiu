<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/19
 * Time: 9:08
 */
namespace App\Services;

use App\Model\Agent;
use App\Model\Config;

class MapServices{

    const INIT_URL = 'https://apis.map.qq.com';

    /**
     * 根据地址匹配精度
     * @param $address
     * @return array
     */
    public static function get_lng_lat_tx($address){

        $config = self::getMapKey();
        $map_key = $config['map_key'];
        $Secret_key = $config['map_secret_key'];

        $init_url = self::INIT_URL;
        $param = "/ws/geocoder/v1/?address=$address&key=$map_key";
        $sig = md5($param.$Secret_key);
        $url = $init_url.$param .'&sig='.$sig;
        $result = self::curl_get($url);
        if($result)
        {
            $data = array();
            $res= json_decode($result,true);
            if ($res['status'] == 0) {
                $results = $res['result'];
                $data['lng'] = $results['location']['lng'];
                $data['lat'] = $results['location']['lat'];
                return $data;
            }else{
                return [];
            }

        }
        else{
            return [];
        }
    }

    /**
     * 匹配距离
     * @param $lng
     * @param $lat
     * @return bool
     */
    public static function distance($lng,$lat){

        $config = self::getMapKey();
        $map_key = $config['map_key'];
        $Secret_key = $config['map_secret_key'];

        $agent = new Agent();
        $agents = $agent->all();
        if($agents){
            $data = [];
            $to_address ='';
            foreach($agents as $k=>$v){
                $data[] = [
                    'scope'=>(int) bcmul($v->distribution_scope,1000),
                    'agent_id'=>$v->id
                ];
                if($k == count($agents)-1){
                    $to_address .= $v->lat.','.$v->lng;
                }else{
                    $to_address .= $v->lat.','.$v->lng.';';
                }
            }


            $param_data = [
                'mode'=>'driving',
                'from'=>"$lat,$lng",
                'to'=>$to_address,
                'key'=>$map_key,
            ];
            $new_param  = self::autograph($param_data);
            $param = "/ws/distance/v1/?".$new_param;
            $sig = md5($param.$Secret_key);
            $url = self::INIT_URL.$param .'&sig='.$sig;
            $result = self::curl_get($url);

            if($result)
            {

                $res= json_decode($result,true);
                dump($res);
                if(isset($res['result'])){
                    $elements = $res['result']['elements'];
                    foreach($elements as $k=>$v){
                        if($v['distance'] <= $data[$k]['scope']){
                            return $data[$k]['agent_id'];
                        }
                    }
                }
                return false;
            }
            else{
                return false;
            }
        }



    }

    public static function autograph($data)
    {
        $str = '';
        $data = array_filter($data);
        ksort($data);
        foreach ($data as $key => $value) {
            $str .= $key . '=' . $value . '&';
        }
        $new_str = substr($str,0,strlen($str)-1);
        return $new_str;
    }
    /**
     * 获取地图key
     * @return array
     */
    public static function getMapKey(){
        $config = new Config();
        $config = $config->getConfig();
        $map_key = $config->map_key;
        $map_secret_key = $config->map_secret_key;
        $data = [
            'map_key'=>$map_key,
            'map_secret_key'=>$map_secret_key,
        ];
        return $data;
    }

    public static function curl_get($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        //参数为1表示传输数据，为0表示直接输出显示。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //参数为0表示不带头文件，为1表示带头文件
        curl_setopt($ch, CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}