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
use App\Model\Friend;
use App\Model\Goods;
use App\Model\IncomeDetails;
use App\Model\Order;
use App\Model\User;
use App\Services\AlibabaSms;
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


        $phone = "15108448660";
//        $result = AlibabaSms::SendSms($phone);
        dd($phone);
        $lng = '39.984154';
        $lat = '116.307490';
       $result = MapServices::get_address($lng,$lat);
       dd($result);

        $friend = new Friend();
        $user = new User();
        $agent = new Agent();
        $user_id = 1;
        $lower = $friend->LowerLevel($user_id);

        $lower = self::array_unset_tt($lower,'parent_id');
        $select = ['user_nickname','user_img','sex','created_at'];
        foreach($lower as $k=>$v){
            $lower[$k]['user_info'] = $user->select($select)->find($v['parent_id']);
            $lower[$k]['count'] = $friend->LowerCount($v['parent_id']);
            $current = $friend->CurrentLevel($v['user_id']);
            $agent = '';
            if($agent){
                $lower[$k]['user_status'] = 1;
            }else{
                $lower[$k]['user_status'] = isset($current['status'])?$current['status']:0;
            }
            $lower[$k]['is_delivery'] = isset($current['is_delivery'])?$current['is_delivery']:0;
            $lower[$k]['contribution_amount'] = $friend->Contribution($v['parent_id']);
            $lower[$k]['user_id'] = $v['parent_id'];
        }

        dd($lower);

        $a = "1,概况,home,index/default,0,statistics,0,1
2,会员,set,member/index,0,member,0,1
3,订单,template,order/index,0,order,0,1
4,商品,component,label/index,0,label,0,1
5,推荐,app,recommend/index,0,recommend,0,1
6,设置,unlink,config/index,0,setup,0,1
7,详细概况,,index/default,1,statistics.list,0,1
8,会员列表,,member/index,2,member.list,0,1
9,订单列,,order/order,3,order.list,0,1
10,推荐列表,,recommend/index,5,recommend.list,0,1
11,程序配置,,config/index,6,setup.list,0,1
12,商品类型,,label/index,4,label.list,0,1
13,代理,notice,agent/index,0,agent,0,1
30,地址,voice,address/index,0,comment,0,1
31,地址管理,,address/index,30,address.list,0,1
32,权限管理,notice,admin/admin,0,admin,0,1
33,管理员,,admin/admin,32,admin.list,0,1
37,商品列表,,goods/index,4,label.destination,0,1
39,关于,about,about/about,0,about.about,0,1
40,关于,,about/about,39,about.about,0,1
42,代理审核,,agent/index,13,agent.index,0,1
43,提现,notice,withdraw/index,0,withdraw.index,0,1
44,提现记录,,withdraw/index,43,withdraw.index,0,1";
        $b = explode("\n",$a);
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