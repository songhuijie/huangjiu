<?php

namespace App\Http\Controllers\Api;

use App\Libraries\Lib_const_status;
use App\Model\Agent;
use App\Model\Config;
use App\Model\Friend;
use App\Model\User;
use App\Services\AccessEntity;
use App\Services\MapServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AgentController extends Controller
{
    private $agent;
    private $config;
    private $friend;
    private $user;
    public function __construct(Agent $agent,Config $config,Friend $friend,User  $user)
    {
        $this->agent = $agent;
        $this->config = $config;
        $this->friend = $friend;
        $this->user = $user;
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
            'city'=>'required|unique:agent',
            'address'=>'required',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
            'phone.mobile'=>Lib_const_status::MOBILE_FORMAT_ERROR,
            'city.unique'=>Lib_const_status::REGION_AGENT_ALREADY_APPLY,
        ]);
        if($fromErr){//输出表单验证错误信息

            return $this->response($fromErr);
        }
        $config = $this->config->getConfig();
        $map_key = $config->map_key;
        $map_secret_key = $config->map_secret_key;
        $detailed_address = $all['city'].$all['address'];
        $map_data = MapServices::get_lng_lat_tx($detailed_address,$map_key,$map_secret_key);
        $response_json = $this->initResponse();
        if(empty($map_data)){
            $response_json->status = Lib_const_status::MAP_ADDRESS_DISCREPANCY;
            return $this->response($response_json);
        }
        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;
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
            $response_json->status = Lib_const_status::USER_AGENT_NOT_EXISTENT;
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
        $lower = $this->friend->LowerLevel($user_id);
        foreach($lower as $k=>$v){
            $lower[$k]->user_info = $this->user->select($select)->find($v->user_id);
            $lower[$k]->count = $this->friend->LowerCount($v->user_id);
        }
        $lower_lower = $this->friend->LowerLowerLevel($user_id);
        foreach($lower_lower as $k=>$v){
            $lower_lower[$k]->user_info = $this->user->select($select)->find($v->user_id);
            $lower_lower[$k]->count = $this->friend->LowerCount($v->user_id);
        }
        $response_json = $this->initResponse();
        $response_json->status = Lib_const_status::SUCCESS;
        $response_json->data = ['first'=>$lower,'second'=>$lower_lower];
        return $this->response($response_json);
    }

}