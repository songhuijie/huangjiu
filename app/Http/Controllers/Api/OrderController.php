<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/21
 * Time: 11:40
 */
namespace App\Http\Controllers\Api;

use App\Events\RoyaltyEvent;
use App\Http\Controllers\Controller;
use App\Libraries\Lib_config;
use App\Libraries\Lib_const_status;
use App\Model\Address;
use App\Model\Agent;
use App\Model\Cart;
use App\Model\Config;
use App\Model\Friend;
use App\Model\Goods;
use App\Model\Order;
use App\Model\User;
use App\Services\AccessEntity;
use App\Services\AlibabaSms;
use App\Services\CourierBirdService;
use App\Services\GoodsService;
use App\Services\RoyaltyService;
use App\Services\WePushService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller{

    private $order;
    private $goods;
    private $address;
    private $agent;
    private $config;
    private $user;
    private $cart;
    private $friend;

    public function __construct(Order $order,Goods $goods,Address $address,Agent $agent,Config $config,User $user,Cart $cart,Friend $friend)
    {
        $this->order = $order;
        $this->goods = $goods;
        $this->address = $address;
        $this->agent = $agent;
        $this->config = $config;
        $this->user = $user;
        $this->cart = $cart;
        $this->friend = $friend;

    }


    /**
     * 下单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function order(Request $request){
        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'goods'=>'required',
            'address_id'=>'required',
            'order_delivery'=>'required',
            'is_arrive'=>'in:1',
            'is_shopping'=>'required:in:0,1',
            'freight'=>'required',
            'over_price'=>'required',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
            'in'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }
        $response_json = $this->initResponse();


        $goods = json_decode($all['goods'],true);

        if(!$goods){
            $response_json->status = Lib_const_status::ORDER_PLACE_FAIL;
            return $this->response($response_json);

        }else{
            $access_entity = AccessEntity::getInstance();
            $user_id = $access_entity->user_id;
            $address = $this->address->getAddress($user_id,$all['address_id']);

            if(!$address){
                $response_json->status = Lib_const_status::USER_ADDRESS_NON_EXISTENT;
                return $this->response($response_json);
            }

            $is_arrive =isset($all['is_arrive'])?$all['is_arrive']:0;
            $arrive_time = isset($all['arrive_time'])?$all['arrive_time']:0;
            $agent_id = isset($all['agent_id'])?$all['agent_id']:0;



            $express = '';
            $express_type = 0;
            if($agent_id != 0){



                $agent = $this->agent->getAgent($agent_id);
                if(!$agent){
                    $response_json->status = Lib_const_status::USER_NOT_AGENT;
                    return $this->response($response_json);
                }
                if($agent->status != 1){
                    $response_json->status = Lib_const_status::USER_AGENT_AUDIT_IN_PROGRESS_OR_FAILED;
                    return $this->response($response_json);
                }
                try{
                    $int = explode(' ',$arrive_time)[1];
                    $time =  strtotime("$int");
                    $start_time = strtotime($agent->start_time);
                    $end_time = strtotime($agent->end_time);
                    if($start_time > $time || $time > $end_time){
                        $response_json->status = Lib_const_status::AGENT_NO_END;
                        return $this->response($response_json);
                    }


                }catch (\Exception $e){
                    $response_json->status = Lib_const_status::AGENT_NO_END;
                    return $this->response($response_json);
                }

                $express = $agent->iphone;
                $express_type = 20;//快递发货状态  代表代理商发货
            }

            $goods_detail = [];
            $total_royalty_price =0;
            $order_total_price =0;
            $order_total_weight =0;
            $order_id =  "T".date('YmdHis') ."R".str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT)."U".$user_id;//订单号
            foreach($goods as $k=>$v){



                $pay_goods = $this->goods->getGoodsBySkuId($k);
                if(!$goods){
                    $response_json->status = Lib_const_status::GOODS_NOT_EXISTENT;
                    return $this->response($response_json);
                }



                $int = GoodsService::UpdateStock($k,Lib_config::GOODS_DEL,$v);
                if($int == 0){
                    $response_json->status = Lib_const_status::GOODS_NOT_ENOUGH_STOCK;
                    return $this->response($response_json);
                }
                if($all['is_shopping'] == 1){
                    $this->cart->updateCartByPay($user_id,$k,$v);
                }

                $good_detail = [
                    'goods_id'=>$pay_goods->id,
                    'good_title'=>$pay_goods->good_title,
                    'good_dsc'=>$pay_goods->good_dsc,
                    'good_type'=>$pay_goods->good_type,
                    'goods_num'=>$v,
                    'goods_weight'=>$pay_goods->weight,
                    'royalty_price'=>$pay_goods->royalty_price,
                    'old_price'=>$pay_goods->old_price,
                    'new_price'=>$pay_goods->new_price,
                    'good_image'=>$pay_goods->good_image,
                ];
                $total_royalty_price += $pay_goods->royalty_price * $v;
                $order_total_price += $pay_goods->new_price * $v;
                $order_total_weight += bcmul($pay_goods->weight,$v);
                $goods_detail[] = $good_detail;
            }
            $total_weight = (int)$order_total_weight;
            $over_total_price = 0;
            if($total_weight > Lib_config::INIT_WEIGHT){
                $over = ceil((float)bcdiv(($total_weight-Lib_config::INIT_WEIGHT),1000,4));
                $over_total_price = $all['over_price']*$over;
            }

            $order_total_price = (float)bcadd($order_total_price,$all['freight'],2);
            $order_total_price = (float)bcadd($order_total_price,$over_total_price,2);
            $order_data = [
                'user_id'=>$user_id,
                'order_delivery'=>isset($all['order_delivery'])?$all['order_delivery']:0,//4  0快递配送 1自提,2配送到家,3配送到店,4送货上门'
                'address_detail'=>json_encode($address),
                'goods_detail'=>json_encode($goods_detail),
                'order_royalty_price'=>$total_royalty_price,
                'order_total_price'=>$order_total_price,
                'is_arrive'=>$is_arrive,
                'arrive_time'=>$arrive_time,
                'agent_id'=>$agent_id,
                'user_name'=>$address->name,
                'user_phone'=>$address->phone,
                'remarks'=>isset($all['remarks'])?$all['remarks']:'',
                'order_number'=>$order_id,
                'freight'=>bcadd($over_total_price,$all['freight'],2),
                'express'=>$express,
                'express_type'=>$express_type,
                'created_at'=>time()
            ];
            $order_ids[] = $this->order->insertOrder($order_data);


            $response_json->status = Lib_const_status::SUCCESS;
            $response_json->data = $order_ids;
            return $this->response($response_json);
        }




    }



    /**
     * 购物结算
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pay(Request $request){

        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'order_id'=>'required',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }

        $config = $this->config->getConfig();

        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;
        $response_json = $this->initResponse();
        $order = $this->order->find($all['order_id']);
        if($order){
//            $this->order->updateStatus($all['order_id'],$user_id,Lib_config::ORDER_STATUS_ONE);
            $goods_detail = $order->goods_detail;

            foreach($goods_detail as $k=>$v){
                GoodsService::updateSellNum($v['goods_id'],$v['goods_num']);
            }

            $openid = $this->user->find($user_id);
            $money = $order->order_total_price;
            $order_number =  $order->order_number;
            $openid = $openid->user_openid;
            $appid       = $config->appid;
            $mch_id      = $config->mch_id;
            $mch_secret       = $config->mch_secret;
            $notify_url  = url('api/v1/notify');//回调地址
            $body        = "小程序下单";
            $attach      = "用户下单";
            $data = initiatingPayment($money,$order_number,$openid,$appid,$mch_id,$mch_secret,$notify_url,$body,$attach);

            Log::info(json_encode($data));
            $response_json->status = Lib_const_status::SUCCESS;
            $response_json->data = $data;
        }else{
            $response_json->status = Lib_const_status::ORDER_NOT_EXISTENT;
        }

        return $this->response($response_json);
    }

    /**
     * 支付回调通知
     */
    public function notify(){
        $value = file_get_contents("php://input"); //接收微信参数
        if (!empty($value)) {
            $arr = xmlToArray($value);
            try{

                if($arr['result_code'] == 'SUCCESS' && $arr['return_code'] == 'SUCCESS'){
                    $attach = json_decode($arr['attach'], true);
                    Log::info($arr['attach']);
                    $money = $arr['total_fee']/100;
                    $uid = $attach['user_id'];
                    $order = $arr['out_trade_no'];






                    $order = $this->order->getOrderByOrderID($order);
                    if($order){
                        if($order->agent_id != 0){

                            //开始配送订单时  推送指定用户
                            //开始配送订单时  推送指定用户
                            $this->order->updateStatusByOrderNumber($order,Lib_config::ORDER_STATUS_TWO);

                            $agent = $this->agent->getAgent($order->agent_id);
                            Log::channel('error')->info('给总代理发送短信:'.$agent->iphone);
                            AlibabaSms::SendSms($agent->iphone);


                            $friend = $this->friend->LowerLevelOne($agent->user_id);

                            $number = [];
                            if($friend){
                                foreach($friend as $k=>$v){
                                    if($v->is_delivery == 1){
                                        $user = $this->user->find($v->parent_id);
                                        if($user && !empty($user->phone_number)){
                                            $number[] = $user->phone_number;

                                        }
                                    }
                                }
                                if($number){
                                    $new_number = array_unique($number);
                                    foreach($new_number as $v){
                                        Log::channel('error')->info('给配送员发送短信:'.$v);
                                        AlibabaSms::SendSms($v);
                                    }
                                }

                            }



                            //开始配送订单时  推送指定用户
                            //开始配送订单时  推送指定用户
                            $thing2 = '';
                            foreach($order->goods_detail as $v){
                                $thing2 .= $v['good_title'].'/';
                            }
                            $thing2 = substr($thing2, 0, -1);
                            $express_type = Lib_config::EXPRESS_TYPE;

                            $express = $order->express;
                            $express_t = isset($express_type[$order->express_type])?$express_type[$order->express_type]:$express_type[1];

                            $message_data = [
                                'character_string1'=>$order->order_number,
                                'thing2'=>$thing2,
                                'thing6'=>$express_t,
                                'phrase4'=>'已配送',
                                'character_string7'=>$express,
                            ];

                            $user = $this->user->find($order->user_id);
                            $open_id=$user->user_openid;

                            WePushService::send_notice(Lib_config::WE_PUSH_TEMPLATE_FIRST,$message_data,$open_id);


                        }else{
                            $this->order->updateStatusByOrderNumber($order,Lib_config::ORDER_STATUS_ONE);
                        }
                    }
                    Log::info('更新成功');
                    // @$this->userController->record($money,$uid,$order);
                    return 'SUCCESS';
                }
            }catch (\Exception $e){
                Log::channel('error')->info($e->getMessage());
            }
        }
    }

    /**
     * 确认收货
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ConfirmReceipt(Request $request){

        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'order_id'=>'required',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }
        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;
        $response_json = $this->initResponse();
        $order = $this->order->getOrderByID($all['order_id'],$user_id);

        if($order){
            switch ($order->order_status){
                case 0:
                    $response_json->status = Lib_const_status::ORDER_TO_BE_PAID;
                    break;
                case 1:
                    $response_json->status = Lib_const_status::ORDER_TO_BE_SHIPPED;
                    break;
                case 2:
                    $response_json->status = Lib_const_status::ORDER_TO_BE_DELIVERED;
                    break;
                case 3:
                    $response_json->status = Lib_const_status::SUCCESS;
                    event(new RoyaltyEvent($user_id,$order->order_royalty_price,$order->is_arrive,$order->agent_id));
                    $this->order->updateStatus($all['order_id'],$user_id,Lib_config::ORDER_STATUS_FOUR);
                    break;
                case 4:
                    $response_json->status = Lib_const_status::ORDER_RECEIVED_GOODS;
                    break;
                default:
                    $response_json->status = Lib_const_status::ORDER_HAS_BEEN_CANCELLED;
                    break;
            }
            //代理分销  或 上级奖励
        }else{
            $response_json->status = Lib_const_status::ORDER_NOT_EXISTENT;
        }
        return $this->response($response_json);

    }

    /**
     * 订单列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function OrderList(Request $request){
        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'status'=>'required|in:0,1,2,3,4',
            'page'=>'int',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
            'int'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }

        $page = isset($all['page'])?$all['page']:Lib_config::PAGE;
        $limit = Lib_config::LIMIT;

        $response_json = $this->initResponse();
        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;
        $orders = $this->order->getOrder($user_id,$all['status'],$page,$limit);
        $response_json->status = Lib_const_status::SUCCESS;
        $response_json->data = $orders;
        return $this->response($response_json);
    }


    /**
     * 取消订单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function OrderCancel(Request $request){
        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'order_id'=>'required',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }

        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;
        $response_json = $this->initResponse();
        $int = $this->order->deleteOrder($user_id,$all['order_id']);
        if($int){
            $response_json->status = Lib_const_status::SUCCESS;
        }else{
            $response_json->status = Lib_const_status::ORDER_NOT_EXISTENT;
        }
        return $this->response($response_json);
    }

    /**
     * 物流信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ExpressInformation(Request $request){
        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'order_id'=>'required',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }

        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;

        $response_json = $this->initResponse();
        $order = $this->order->find($all['order_id']);

        if($order->order_delivery == 0){
            $Order_Code = $order->express;
            $type = $order->express_type;
            $express_info = CourierBirdService::getOrderTracesByJson($Order_Code,$type);
            $agent = [];
        }else{
            $express_info = [];
            $agent = $this->agent->find($order->agent_id);
        }

        $response_json->status = Lib_const_status::SUCCESS;
        $response_json->data->express = $express_info;
        $response_json->data->agent = $agent;
        $response_json->data->order = $order;
        return $this->response($response_json);
    }

}