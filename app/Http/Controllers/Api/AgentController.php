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
use App\Services\MapServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
     * 获取下级用户
     */
    public function SubordinateUser(){

        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;
        $select = ['user_nickname','user_img','sex'];



        $response_json = $this->initResponse();
        $agent = $this->agent->getByUserID($user_id);
        if(!$agent){
            $response_json->status = Lib_const_status::USER_NOT_AGENT;
            return $this->response($response_json);
        }
        if($agent->status != 1){
            $response_json->status = Lib_const_status::USER_AGENT_AUDIT_IN_PROGRESS_OR_FAILED;
            return $this->response($response_json);
        }

        $lower = $this->friend->LowerLevel($user_id);
        foreach($lower as $k=>$v){
            $lower[$k]->user_info = $this->user->select($select)->find($v->user_id);
            $lower[$k]->count = $this->friend->LowerCount($v->user_id);
        }
        $lower_lower = $this->friend->LowerLowerLevel($user_id,2);
        foreach($lower_lower as $k=>$v){
            $lower_lower[$k]->user_info = $this->user->select($select)->find($v->user_id);
        }
        $best_lower = $this->friend->LowerLowerLevel($user_id,3);
        foreach($lower_lower as $k=>$v){
            $best_lower[$k]->user_info = $this->user->select($select)->find($v->user_id);
        }

        $response_json->status = Lib_const_status::SUCCESS;
        $response_json->data = ['first'=>$lower,'second'=>$lower_lower,'third'=>$best_lower];
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
                if($type == 2){
                    if($friend->parent_parent_id == 0 && $friend->best_id == 0){
                        $response_json->status = Lib_const_status::USER_CAN_NOT_BECOME_SECOND;
                        return $this->response($response_json);
                    }
                }else{
                    if($friend->best_id != $user_id){
                        $response_json->status = Lib_const_status::USER_CAN_NOT_BECOME_THIRD;
                        return $this->response($response_json);
                    }
                }
                $this->friend->updateAgent($set_user_id,$type,$type);
                $response_json->status = Lib_const_status::SUCCESS;
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
            'friend_id'=>'required|int',
            'type'=>'int|in:1,2',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
            'int'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }

        $friend_id = $all['friend_id'];

        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;
        $response_json = $this->initResponse();

        $type = isset($all['type'])?$all['type']:1;
        $friend = $this->friend->GetFriendByBestOrParent($friend_id);
        if($friend){
            if($type != 1){
                if($friend->parent_parent_id == $user_id || $friend->best_id == $user_id){
                    $this->friend->updateAgentByID($friend->id,$type,Lib_config::AGENT_STATUS_NO);
                    $response_json->status = Lib_const_status::SUCCESS;
                }else{
                    $response_json->status = Lib_const_status::USER_CAN_NOT_BECOME;
                }
            }else{
                $this->friend->updateAgentByID($friend->id,$type,Lib_config::AGENT_STATUS_NO);
                $response_json->status = Lib_const_status::SUCCESS;
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
     * 获取代理订单列表
     */
    public function getAgentList(Request $request){

        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'status'=>'required|in:2,3,4',
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

        if($friend){
            if($friend->is_delivery == 1){
                $agent_user_id = ($friend->best_id == 0) ? ($friend->parent_parent_id==0)?$friend->parent_id:$friend->parent_parent_id:$friend->best_id;

                $agent = $this->agent->getByUserID($agent_user_id,1);
                if($agent){
                    $order = $this->order->getWhereByStatus($agent->id,$all['status']);
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
                $order = $this->order->getWhereByStatus($agent->id,$all['status']);
                $response_json->status = Lib_const_status::SUCCESS;
                $response_json->data = $order;
            }else{
                $response_json->status = Lib_const_status::USER_NOT_AGENT;
            }
        }



        return $this->response($response_json);
    }

}