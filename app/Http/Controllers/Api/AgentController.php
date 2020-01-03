<?php

namespace App\Http\Controllers\Api;

use App\Libraries\Lib_config;
use App\Libraries\Lib_const_status;
use App\Model\Agent;
use App\Model\AgentSet;
use App\Model\Config;
use App\Model\Friend;
use App\Model\Order;
use App\Model\User;
use App\Services\AccessEntity;
use App\Services\AlibabaSms;
use App\Services\CityServices;
use App\Services\MapServices;
use App\Services\RoyaltyService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class AgentController extends Controller
{
    private $agent;
    private $agent_set;
    private $config;
    private $friend;
    private $user;
    private $order;
    public function __construct(Agent $agent,AgentSet $agent_set,Config $config,Friend $friend,User  $user,Order $order)
    {
        $this->agent = $agent;
        $this->agent_set = $agent_set;
        $this->config = $config;
        $this->friend = $friend;
        $this->user = $user;
        $this->order = $order;
    }

    /**
     * 申请代理
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function Apply(Request $request){
        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'user_name'=>'required',
            'iphone'=>'required|mobile',
            'city'=>'required',
            'address'=>'required',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
            'phone.mobile'=>Lib_const_status::MOBILE_FORMAT_ERROR,
        ]);
        if($fromErr){//输出表单验证错误信息

            return $this->response($fromErr);
        }
        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;



        $detailed_address = $all['city'].$all['address'];
        $map_data = MapServices::get_lng_lat_tx($detailed_address);
        $response_json = $this->initResponse();
        if(empty($map_data)){
            $response_json->status = Lib_const_status::MAP_ADDRESS_DISCREPANCY;
            return $this->response($response_json);
        }
        $city_agent = $this->agent->getAgentByCity($all['city']);
        if($city_agent){
            $response_json->status = Lib_const_status::REGION_AGENT_ALREADY_APPLY;
            return $this->response($response_json);
        }
        $all['user_id']=$user_id;
        $all['lng']=$map_data['lng'];
        $all['lat']=$map_data['lat'];
        $int = $this->agent->insertAgent($all,$user_id);
        if($int){
            $response_json->status = Lib_const_status::SUCCESS;
        }else{
            $response_json->status = Lib_const_status::USER_AGENT_ALREADY_APPLY;
        }
        return $this->response($response_json);

    }

    /**
     * 代理 信息设置
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function Setting(Request $request){
        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'agent_id'=>'required',
            'start_time'=>'required',
            'end_time'=>'required',
            'distribution_scope'=>'required',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
            'phone.mobile'=>Lib_const_status::MOBILE_FORMAT_ERROR,
            'city.unique'=>Lib_const_status::REGION_AGENT_ALREADY_APPLY,
        ]);
        if($fromErr){//输出表单验证错误信息

            return $this->response($fromErr);
        }

        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;
        $agent_id = $all['agent_id'];
        unset($all['agent_id']);
        $int = $this->agent->updateAgent($all,$user_id,$agent_id);
        $response_json = $this->initResponse();
        if($int){
            $response_json->status = Lib_const_status::SUCCESS;
        }else{
            $response_json->status = Lib_const_status::USER_NOT_AGENT;
        }
        return $this->response($response_json);
    }


    /**
     * 获取代理
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAgent(Request $request){
        $all = $request->all();
        $city = isset($all['city'])? $all['city']:false;

        $agents = $this->agent->getNeedAgent($city);
        $response_json = $this->initResponse();
        $response_json->status = Lib_const_status::SUCCESS;
        $response_json->data = $agents;
        return $this->response($response_json);
    }



    /**
     * 数组去重
     */
    public static function array_unset_tt($arr,$key){
        //建立一个目标数组
        $res = array();
        foreach ($arr as $value) {
            //查看有没有重复项
            if(isset($res[$value[$key]])){
                unset($value[$key]);  //有：销毁
            }else{
                $res[$value[$key]] = $value;
            }
        }
        return $res;
    }

    /**
     * 获取下级用户
     */
    public function SubordinateUser(){

        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;
        $select = ['user_nickname','user_img','sex','created_at'];



        $response_json = $this->initResponse();
        $agent = $this->agent->getByUserID($user_id);
        if($agent && $agent->status == 1){
            $status = 1;
        }else{
            $init_friend = $this->friend->GetFriend($user_id);
            if($init_friend && $init_friend->status != 0){
                $status = 2;
            }else{
                $status = 0;
            }
        }

        if($status != 0){
            $lower = $this->friend->LowerLevel($user_id);
            $lower = array_values(self::array_unset_tt($lower,'user_id'));
            foreach($lower as $k=>$v){
                if($v['user_id'] == 0){
                    unset($lower[$k]);
                }else{
                    $lower[$k]['user_info'] = $this->user->select($select)->find($v['user_id']);
                    $lower[$k]['created_at'] = $lower[$k]['user_info']->created_at;
                    $lower[$k]['count'] = $this->friend->LowerCount($v['user_id']);
                    $current = $this->friend->CurrentLevel($v['user_id']);
                    $agent = $this->agent->getByUserID($v['user_id'],1);
                    if($agent){
                        $lower[$k]['user_status'] = 1;
                    }else{
                        $lower[$k]['user_status'] = isset($current['status'])?$current['status']:0;
                    }
                    $lower[$k]['is_delivery'] = isset($current['is_delivery'])?$current['is_delivery']:0;

                    $lower[$k]['contribution_amount'] = $this->friend->Contribution($v['user_id'],$user_id);
                }
            }

            $response_json->status = Lib_const_status::SUCCESS;
            $response_json->data = $lower;
        }else{
            $response_json->status = Lib_const_status::USER_NOT_BECOME;
            $response_json->data = 1;
        }



        return $this->response($response_json);
    }

    /**
     * 设置代理
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setAgent(Request $request){

        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'user_id'=>'required|int',
            'type'=>'int|in:1,2,3',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
            'int'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }
        $set_user_id = $all['user_id'];
        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;

        $response_json = $this->initResponse();
        $agent = $this->agent->getByUserID($user_id,1);
        $type = isset($all['type'])?$all['type']:1;
        if($agent){
            $friend = $this->friend->GetFriend($set_user_id);

            if($friend){
                switch ($type){
                    case 1:
                    case 2:
                        $this->friend->updateAgent($set_user_id,$type,$type);
                        $response_json->status = Lib_const_status::SUCCESS;
                        break;
                    case 3:
                        if($friend->best_id == $user_id){
                            $this->friend->updateAgent($set_user_id,$type,$type);
                            $response_json->status = Lib_const_status::SUCCESS;
                        }else{
                            $response_json->status = Lib_const_status::USER_CAN_NOT_BECOME_THIRD;
                        }
                        break;
                    default:
                        break;
                }

            }else{
                $friend = $this->friend->GetFriendInit($set_user_id);
                if($friend){
                    if($friend->parent_id == $user_id || $friend->parent_parent_id == $user_id || $friend->best_id == $user_id){
                        $this->friend->InsertFriend($set_user_id, $friend->parent_id,$friend->parent_parent_id,$friend->best_id, $type);
                        $response_json->status = Lib_const_status::SUCCESS;

                    }else{
                        $response_json->status = Lib_const_status::USER_IS_NOT_TEAM;
                    }

                }else{
                    $response_json->status = Lib_const_status::USER_NOT_EXISTENT;

                }
            }
        }else{
            $response_json->status = Lib_const_status::USER_NOT_AGENT;
        }
        return $this->response($response_json);
    }

    /**
     * 取消代理
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelAgent(Request $request){

        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'user_id'=>'required|int',
            'type'=>'int|in:1,2',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
            'int'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }

        $set_user_id = $all['user_id'];

        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;
        $response_json = $this->initResponse();

        $type = isset($all['type'])?$all['type']:1;
        $friend = $this->friend->GetFriendByBestOrParent($set_user_id);
        if($friend){
            if($user_id == $friend->parent_parent_id || $user_id == $friend->best_id){
                $this->friend->updateAllAgentByID($set_user_id,Lib_config::AGENT_STATUS_NO);
                $response_json->status = Lib_const_status::SUCCESS;
            }else{
                $response_json->status = Lib_const_status::USER_NOT_BECOME;
            }
        }else{
            $response_json->status = Lib_const_status::USER_CAN_NOT_BECOME;
        }

        return $this->response($response_json);
    }

    /**
     * 根据精度位置获取代理
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function AgentAccuracy(Request $request){

        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'lat'=>'required',
            'lng'=>'required',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }

        $lat = $all['lat'];
        $lng = $all['lng'];

        $agent_id = MapServices::distance($lng,$lat);


        $response_json = $this->initResponse();
        if($agent_id){
            $agents = $this->agent->getAgent($agent_id);
            $agents->user_Img = $agents->userImg->user_img;
            unset($agents->userImg);
            $response_json->status = Lib_const_status::SUCCESS;
            $response_json->data = $agents;
        }
        $response_json->status = Lib_const_status::SUCCESS;
        return $this->response($response_json);
    }

    /**
     * 根据经纬度获取
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function AccuracyAddress(Request $request){
        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'lat'=>'required',
            'lng'=>'required',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }
        $lat = $all['lat'];
        $lng = $all['lng'];

        $address = MapServices::get_address($lng,$lat);

        $response_json = $this->initResponse();
        $response_json->status = Lib_const_status::SUCCESS;
        $response_json->data = $address;
        return $this->response($response_json);

    }
    /**
     * 获取代理订单列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAgentList(Request $request){

        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'status'=>'required|in:2,3,4',
            'page'=>'int'
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
            'in'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }

        $page = isset($all['page'])?$all['page']:Lib_config::PAGE;
        $limit = Lib_config::LIMIT;
        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;
        $response_json = $this->initResponse();


        $agent = $this->agent->getByUserID($user_id,1);
        if($agent){
            $order = $this->order->getWhereByStatus($agent->id,$all['status'],$page,$limit);
            $response_json->status = Lib_const_status::SUCCESS;
            $response_json->data = $order;
        }else{
            $friend = $this->friend->GetFriend($user_id);

            if($friend){

                if($friend->is_delivery == 1 || $friend->status != 0){
                    $agent_user_id = ($friend->best_id == 0) ? ($friend->parent_parent_id==0)?$friend->parent_id:$friend->parent_parent_id:$friend->best_id;

                    $agent = $this->agent->getByUserID($agent_user_id,1);
                    if($agent){
                        $order = $this->order->getWhereByStatus($agent->id,$all['status'],$page,$limit);
                        $response_json->status = Lib_const_status::SUCCESS;
                        $response_json->data = $order;
                    }else{
                        $response_json->status = Lib_const_status::USER_NOT_AGENT;
                        $response_json->data = $agent_user_id;
                        $response_json->data->friend = $friend;
                    }

                }else{
                    $response_json->status = Lib_const_status::USER_CAN_NOT_DELIVER;
                }

            }else{
                $agent = $this->agent->getByUserID($user_id,1);
                if($agent){
                    $order = $this->order->getWhereByStatus($agent->id,$all['status'],$page,$limit);
                    $response_json->status = Lib_const_status::SUCCESS;
                    $response_json->data = $order;
                }else{
                    $response_json->status = Lib_const_status::USER_NOT_AGENT;
                }
            }
        }

        return $this->response($response_json);
    }


    /**
     * 代理 更改订单状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeOrder(Request $request){
        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'order_id'=>'required',
            'status'=>'required|in:3,4',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
            'in'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }

        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;

        $response_json = $this->initResponse();
        $friend = $this->friend->GetFriend($user_id);
        $agent = $this->agent->getByUserID($user_id,1);
        $order = $this->order->find($all['order_id']);
        if($agent){

            $this->order->updateStatusByAgent($all['order_id'],$agent->id,$all['status']);
            if($all['status'] == 3){
//                $phone = $agent->iphone;
//                $friends = $this->friend->GetFriend($friend->parent_id);
//                if($friends){
//                    $friend = $friends;
//                }else{
//                    $friend = $this->friend->GetFriendInit($order->user_id);
//                    $friend = $this->friend->GetFriend($friend->parent_id);
//                }
//                $delivery_phone = null;
//                if($friend){
//
//                    if($friend->is_delivery == 1){
//                        $user = $this->user->find($friend->parent_id);
//                        if($user->phone_number){
//                            $delivery_phone = $user->phone_number;
//                            $phone_json = "[\"$phone\",\"$delivery_phone\"]";
//                        }else{
//                            $phone_json = "[\"$phone\"]";
//                        }
//                    }else{
//                        $phone_json = "[\"$phone\"]";
//                    }
//                }else{
//                    $phone_json = "[\"$phone\"]";
//                }
//
//                AlibabaSms::SendSms($phone);
//                if($delivery_phone){
//                    AlibabaSms::SendSms($delivery_phone);
//                }

            }
            if($all['status'] == 4){
                //处理商品提成
                RoyaltyService::HandleRoyalty($order->user_id,$order->order_royalty_price,$order->is_arrive,$order->agent_id);
            }
            $response_json->status = Lib_const_status::SUCCESS;
        }else{
            if($friend){

                if($friend->is_delivery == 1 || $friend->status != 0){
                    $agent_user_id = ($friend->best_id == 0) ? ($friend->parent_parent_id==0)?$friend->parent_id:$friend->parent_parent_id:$friend->best_id;

                    $agent = $this->agent->getByUserID($agent_user_id,1);
                    if($agent){
                        $data = [
                            'order_id'=>$all['order_id'],
                            'user_id'=>$agent_user_id,
                            'agent_id'=>$agent->id,
                            'status'=>$all['status'],
                        ];
                        $int = $this->order->updateStatusByAgent($all['order_id'],$agent->id,$all['status']);
                        if($all['status'] == 4){
                            //处理商品提成
                            RoyaltyService::HandleRoyalty($order->user_id,$order->order_royalty_price,$order->is_arrive,$order->agent_id);
                        }
                        $response_json->status = Lib_const_status::SUCCESS;
                        $response_json->data = $data;
                    }else{
                        $response_json->status = Lib_const_status::USER_NOT_AGENT;
                    }

                }else{
                    $response_json->status = Lib_const_status::USER_CAN_NOT_DELIVER;
                }

            }
        }


        return $this->response($response_json);
    }


    /**
     * 根据城市获取运费
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFreight(Request $request){

        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'city'=>'required',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }
        $price = CityServices::getCity($all['city']);
        if($price === false){
            $price = 0;
            $over_price=0;
        }
        $response_json = $this->initResponse();
        $response_json->status = Lib_const_status::SUCCESS;
        $response_json->data->freight = (float) $price['price'];
        $response_json->data->over_price = (float) $price['over_price'];
        return $this->response($response_json);

    }
}