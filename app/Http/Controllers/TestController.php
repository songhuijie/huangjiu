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
use App\Model\Asset;
use App\Model\Collection;
use App\Model\Goods;
use App\Model\IncomeDetails;
use App\Model\Order;
use App\Model\User;
use App\Services\CourierBirdService;
use App\Services\MapServices;

class TestController extends Controller{

    public function test(){
//
//        $address ='北京';
//        $key = '76JBZ-55A6W-3YURV-RO4FM-D7HRE-JDFW6';
//        $Secret_key = 'LGS1AUdf7Q7qB9fTBVF7Ofv1DiebARAr';
//        $result = MapServices::get_lng_lat_tx($address,$key,$Secret_key);
//        dd($result);
//        $agent = new Agent();
//        $agents = $agent->getAgent(1);
//        $agents->user_Img = $agents->userImg->user_img;
//        unset($agents->userImg);
//        $response_json = new \StdClass();
//        $response_json->data = $agents;
//        return $this->response($response_json);
        //lat: "30.69015"
        //lng: "104.05293"

        $a = '2019-12-27 14:30:00';
//        $time =  explode(':',explode(' ',$a)[1]);
        $start_time = explode(':','14:30:29');
        $end_time = explode(':','22:39:18');
        try{
            $time =  explode(':',explode(' ',$a)[1]);
            if($start_time[0] > $time[0] || $time[0] > $end_time[0]){
                dd(1);
            }
            if($start_time[1] > $time[1] || $time[1] > $end_time[1]){
                dd(3);
            }
            dd(2);
        }catch (\Exception $e){
            echo '时间格式不对';

        }
        dd(1);
        $order = new Order();
        $order = $order->find(1);
        $goods_detail =  $order->goods_detail;
        $goods_ids = array_column($goods_detail,'good_title','goods_id');
        dd($goods_ids,isset($goods_ids[1]));
        $order_id = '773020792446578';
        $result = CourierBirdService::getOrderTracesByJson($order_id,4);
        dd($result);
        $lng = 104.037071;
        $lat = 30.67001;
        $agent_id = MapServices::distance($lng,$lat);
        dd($agent_id);
        $good = new Goods();
        $goods_id = 1;
        $type = 2;
        $num = 1;
        $result = $good->updateStock($goods_id,$type,$num);
        dd($result);

        $user = new User();
        $access_token = 'access_token1234567';
        $token_array = $user->getByAccessToken($access_token);
        dd($token_array);

        $a = "1,概况,home,index/default,0,statistics,0,1
2,会员,set,member/index,0,member,0,1
3,订单,template,order/index,0,order,0,1
4,商品,component,label/index,0,label,0,1
5,推荐,app,recommend/index,0,recommend,0,1
6,设置,unlink,config/index,0,setup,0,1
7,详细概况,,index/default,1,statistics.list,0,1
8,会员列表,,member/index,2,member.list,0,1
9,订单列表,,order/order,3,order.list,0,1
30,评论,voice,comment/index,0,comment,0,1
10,推荐列表,,recommend/index,5,recommend.list,0,1
11,程序配置,,config/index,6,setup.list,0,1
31,评论管理,,comment/index,30,comment.list,0,1
12,商品类型,,label/index,4,label.list,0,1
32,权限管理,notice,admin/admin,0,admin,0,1
33,管理员,,admin/admin,32,admin.list,0,1
34,图片-图标管理,,config/picture,6,setup.picture,0,1
35,图片类型,,config/type,6,config.type,0,1
37,商品列表,,goods/index,4,label.destination,0,1
38,定制需求,,config/essential,6,config.essential,0,1
39,角色管理,,admin/role,32,admin.role,0,1";
        $b = explode("\r\n",$a);
        $datas = [];
        $data = [];
        foreach($b as $k=>$v){
            echo '[';
            echo "<br/>";
            echo "'id'=>'".explode(',',$v)[0]."',";echo "<br/>";
            echo "'menuname'=>'".explode(',',$v)[1]."',";echo "<br/>";
            echo "'icon'=>'".explode(',',$v)[2]."',";echo "<br/>";
            echo "'url'=>'".explode(',',$v)[3]."',";echo "<br/>";
            echo "'pid'=>'".explode(',',$v)[4]."',";echo "<br/>";
            echo "'rout'=>'".explode(',',$v)[5]."',";echo "<br/>";
            echo "'time'=>'".explode(',',$v)[6]."',";echo "<br/>";
            echo "'status'=>'".explode(',',$v)[7]."',";echo "<br/>";
            echo '],';echo "<br/>";
        }
        dd(1);
        $asset  = new Asset();
        $income  = new IncomeDetails();

        $amount = $income->getAmount(1);
        if($amount){
            dd('有');
        }else{
            dd('没');
        }
//        $sum = $asset->sum('royalty_balance');
        dd($amount,$amount == null);
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