<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/23
 * Time: 16:06
 */
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Libraries\Lib_config;
use App\Model\Agent;
use App\Model\Friend;
use App\Model\Order;
use App\Model\User;
use App\Services\GoodsService;
use App\Services\GoodTypeService;
use App\Services\RoyaltyService;
use App\Services\WePushService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller{

    private  $type = [
        0=>'待支付',
        1=>'支付成功待发货',
        2=>'待配送',
        3=>'已发货',
        4=>'完成',
        5=>'退款',
        6=>'取消',
    ];
    private $order;
    private $user;
    private $agent;
    private $friend;
    public function __construct(Order $order,User $user,Agent $agent,Friend $friend)
    {
        $this->order = $order;
        $this->user = $user;
        $this->agent = $agent;
        $this->friend = $friend;
    }

    public function index(Request $request){

        if(!empty($request->input('type'))){
            $data=$request->all();
            if($data['type']=="select"){
                $page = Lib_config::PAGE;
                $size = Lib_config::LIMIT;
                if(!empty($data['page'])){
                    $page=$data['page'];
                    $size=$data['limit'];
                }
                $order = $this->order->getWhere($data);
                return array('code'=>0,'msg'=>'获取到数据','limit'=>$size,'page'=>$page,'count'=>$order['count'],'data'=>$order['data']);
            }

//            if($data['type']='edit'){
//                unset($data['type']);
//                $label=DB::table("order")->where("id","=",$data['id'])->first();
//                if($label->status==1){
//                    $data['status']=0;
//                }else{
//                    $data['status']=1;
//                }
//
//                $reust = DB::table("order")->where("id","=",$data['id'])->update($data);
//                if($reust){
//                    return array("code"=>1,"msg"=>"修改成功");
//                }else{
//                    return array("code"=>0,"msg"=>"修改失败,请重试");
//                }
//
//            }

        }

        return view("admin/order/index",['type'=>$this->type]);
    }
    //删除
    public function status(Request $request){
        $id=$request->input('id');
        if($request->input('type')=='del'){
            $result=DB::table("user")->where('id',$id)->delete();
        }
        if($result){
            return array('code'=>1,'msg'=>'删除成功');
        }else{
            return array('code'=>0,'msg'=>'删除失败');
        }
    }

    public function detail(Request $request){

        if(!empty($request->input('type'))){
            $data=$request->all();
            if($data['type']=='edit'){

                if(!empty($data['update'])){
                    $id = $data['id'];
                    unset($data['update'],$data['type'],$data['id']);
                    $order = $this->order->find($id);
                    if($data['order_status'] == Lib_config::ORDER_STATUS_FOUR){
                        //处理商品提成
                        RoyaltyService::HandleRoyalty($order->user_id,$order->order_royalty_price,$order->is_arrive,$order->agent_id);

                        //签收订单后  推送指定用户
                        $thing2 = '';
                        foreach($order->goods_detail as $v){
                            $thing2 .= $v['good_title'].' * '.$v['goods_num'].'/';
                        }
                        $thing2 = substr($thing2, 0, -1);




                        $user = $this->user->find($order->user_id);
                        $user_name = isset($user->user_nickname)?$user->user_nickname:'张三';
                        $message_data = [
                            'character_string1'=>$order->order_number,
                            'thing2'=>$thing2,
                            'time3'=>date('Y-m-d H:i:s'),
                            'name4'=>$user_name,
                        ];

                        if($order->agent_id != 0){

                            $agent = $this->agent->getAgent($order->agent_id);
                            if($agent){
                                $agent_user_id = $agent->user_id;
                                $lower = $this->friend->LowerLevel($agent_user_id);


                                $lower = array_values(array_unset_tt($lower,'parent_id'));

                                $send_ids = [];
                                foreach($lower as $k=>$v){
                                    if($v['user_id'] == 0){
                                        unset($lower[$k]);
                                    }else{
                                        $current = $this->friend->CurrentLevel($v['user_id']);
                                        if($current){
                                            if($current->status != 0 || $current->is_delivery != 0){
                                                $send_ids[] = $v['user_id'];
                                            }
                                        }
                                    }
                                }


                                if($send_ids){
                                    $users = $this->user->select('user_openid')->where('id',$send_ids)->get()->toArray();

                                    $user_openids = array_column($users,'user_openid');

                                    foreach($user_openids as $v){
                                        WePushService::send_notice(Lib_config::WE_PUSH_TEMPLATE_SECOND,$message_data,$v);
                                    }
                                }

                            }
                        }


                        WePushService::send_notice(Lib_config::WE_PUSH_TEMPLATE_SECOND,$message_data);
                    }

                    if($data['order_status'] == Lib_config::ORDER_STATUS_TWO){


                        //开始配送订单时  推送指定用户
                        $thing2 = '';
                        foreach($order->goods_detail as $v){
                            $thing2 .= $v['good_title'].'/';
                        }
                        $thing2 = substr($thing2, 0, -1);
                        $express_type = Lib_config::EXPRESS_TYPE;

                        $express = $data['express'];
                        $express_t = isset($express_type[$data['express_type']])?$express_type[$data['express_type']]:$express_type[1];

                        $message_data = [
                            'character_string1'=>$order->order_number,
                            'thing2'=>$thing2,
                            'thing6'=>$express_t,
                            'phrase4'=>'已配送',
                            'character_string7'=>$express,
                        ];

                        if($order->agent_id != 0){

                            $agent = $this->agent->getAgent($order->agent_id);
                            if($agent){
                                $agent_user_id = $agent->user_id;
                                $lower = $this->friend->LowerLevel($agent_user_id);


                                $lower = array_values(array_unset_tt($lower,'parent_id'));

                                $send_ids = [];
                                foreach($lower as $k=>$v){
                                    if($v['user_id'] == 0){
                                        unset($lower[$k]);
                                    }else{
                                        $current = $this->friend->CurrentLevel($v['user_id']);
                                        if($current){
                                            if($current->status != 0 || $current->is_delivery != 0){
                                                $send_ids[] = $v['user_id'];
                                            }
                                        }
                                    }
                                }


                                if($send_ids){
                                    $users = $this->user->select('user_openid')->where('id',$send_ids)->get()->toArray();

                                    $user_openids = array_column($users,'user_openid');

                                    foreach($user_openids as $v){
                                        WePushService::send_notice(Lib_config::WE_PUSH_TEMPLATE_FIRST,$message_data,$v);
                                    }
                                }

                            }
                        }

                        WePushService::send_notice(Lib_config::WE_PUSH_TEMPLATE_FIRST,$message_data);

                    }

                    $reust = $this->order->where(['id'=>$id])->update($data);
                    if($reust){
                        return array("code"=>1,"msg"=>"修改成功","status"=>1);
                    }else{
                        return array("code"=>0,"msg"=>"修改失败","status"=>1);
                    }
                }
                $order_info =$this->order->find($data['id']);
                $express_type = Lib_config::EXPRESS_TYPE;
                return view("admin/order/detail",['type'=>$this->type,'order'=>$order_info,'express_type'=>$express_type]);
            }elseif($data['type'] == 'view'){
                $order_info =$this->order->find($data['id']);
                return view("admin/order/view",['type'=>$this->type,'order'=>$order_info]);
            }

        }

        return view("admin/order/detail",['type'=>$this->type]);
    }
}