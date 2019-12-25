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
use App\Model\Goods;
use App\Model\Order;
use App\Services\AccessEntity;
use App\Services\GoodsService;
use App\Services\RoyaltyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller{

    private $order;
    private $goods;
    private $address;
    private $agent;

    public function __construct(Order $order,Goods $goods,Address $address,Agent $agent)
    {
        $this->order = $order;
        $this->goods = $goods;
        $this->address = $address;
        $this->agent = $agent;

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
            'is_arrive'=>'in:1',
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
            if($agent_id != 0){
                $agent = $this->agent->getAgentById($agent_id,$user_id);
                if(!$agent){
                    $response_json->status = Lib_const_status::USER_AGENT_NOT_EXISTENT;
                    return $this->response($response_json);
                }
            }

            $order_ids = [];
            foreach($goods as $k=>$v){
                $order_id = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);

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
                $order_data = [
                    'user_id'=>$user_id,
                    'order_delivery'=>0,//4  0快递配送 1自提,2配送到家,3配送到店,4送货上门'
                    'address_detail'=>json_encode($address),
                    'goods_id'=>$pay_goods->id,
                    'goods_type'=>$pay_goods->good_type,
                    'order_image'=>$pay_goods->good_image,
                    'order_name'=>$pay_goods->good_title,
                    'order_num'=>$v,
                    'order_price'=>$pay_goods->new_price,
                    'order_royalty_price'=>$pay_goods->royalty_price,
                    'order_total_price'=>bcmul($v,$pay_goods->new_price,2),
                    'is_arrive'=>$is_arrive,
                    'arrive_time'=>$arrive_time,
                    'agent_id'=>$agent_id,
                    'user_name'=>$address->name,
                    'user_phone'=>$address->phone,
                    'order_number'=>$order_id,
                ];
                $order_ids[] = $this->order->insertOrder($order_data);
            }

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

        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;
        $response_json = $this->initResponse();
        $order = $this->order->find($all['order_id']);
        if($order){

            $this->order->updateStatus($all['order_id'],$user_id,Lib_config::ORDER_STATUS_ONE);
            $response_json->status = Lib_const_status::SUCCESS;
        }else{
            $response_json->status = Lib_const_status::ORDER_NOT_EXISTENT;
        }

        return $this->response($response_json);
    }

    /**
     * 支付回调通知
     */
    public function notify(){

    }

    /**
     * 确认收货
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
        $order = $this->order->find($all['order_id']);
        if($order){

            event(new RoyaltyEvent($user_id,$order->order_royalty_price,$order->is_arrive,$order->agent_id));

            $this->order->updateStatus($all['order_id'],$user_id,Lib_config::ORDER_STATUS_FOUR);
            $response_json->status = Lib_const_status::SUCCESS;
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
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }

        $response_json = $this->initResponse();
        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;
        $orders = $this->order->getOrder($user_id,$all['status']);
        $response_json->status = Lib_const_status::SUCCESS;
        $response_json->data = $orders;
        return $this->response($response_json);
    }
}