<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/17
 * Time: 18:03
 */
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\About;
use App\Model\Agent;
use App\Model\Collection;
use App\Model\Goods;
use App\Services\MapServices;

class TestController extends Controller{

    public function test(){
//
//        $address ='北京';
//        $key = '76JBZ-55A6W-3YURV-RO4FM-D7HRE-JDFW6';
//        $Secret_key = 'LGS1AUdf7Q7qB9fTBVF7Ofv1DiebARAr';
//        $result = MapServices::get_lng_lat_tx($address,$key,$Secret_key);
//        dd($result);

        $collect = new Agent();

        $all =[
            "user_name"=>"杰大哥",
            "iphone"=>"17880952663",
            "city"=>"四川省成都市新都区",
            "address"=>"斑竹园",
            "user_id"=>1,
            "lng"=>104.061378,
            "lat"=>30.81497
        ];
        $user_id = 1;
        $result = $collect->insertAgent($all,$user_id);
        dd($result);
//        $goods = new Goods();
//
//        $goods->updateStock(9,2,1);
//        dd(1);
        return   $this->response( $this->initResponse());
    }
}