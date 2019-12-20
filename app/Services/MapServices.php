<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/19
 * Time: 9:08
 */
namespace App\Services;

class MapServices{


    public static function get_lng_lat_tx($address,$map_key,$Secret_key){


        $init_url = 'https://apis.map.qq.com';
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