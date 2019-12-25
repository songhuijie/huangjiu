<?php

namespace App\Http\Controllers\Api;

use App\Libraries\Lib_const_status;
use App\Model\Address;
use App\Model\Config;
use App\Services\AccessEntity;
use App\Services\MapServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use App\Model\User;
use PharIo\Manifest\Library;


class AddressController extends Controller
{
    private $user;
    private $address;
    private $config;
    public function __construct(User $user,Address $address,Config $config)
    {
        $this->user = $user;
        $this->address = $address;
        $this->config = $config;
    }

    /**
     * 获取用户地址 列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addressList(Request $request)
    {
        $access_entity = AccessEntity::getInstance();

        $user_id = $access_entity->user_id;
        $address_id = $request->input('address_id');
        if($address_id){
            $user_address = $this->address->getAddress($user_id,$address_id);
            $address[] = $user_address;
        }else{
            $address = $this->address->getALl($user_id);
        }

        $response_json = $this->initResponse();
        $response_json->code = Lib_const_status::CORRECT;
        $response_json->status = Lib_const_status::SUCCESS;
        $response_json->data = $address;
        return $this->response($response_json);
    }

    /**
     * 添加用户收货地址
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addressAdd(Request $request){

        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'province'=>'required',
            'city'=>'required',
            'area'=>'required',
            'address'=>'required',
            'name'=>'required',
            'phone'=>'required|mobile',
            'defaults'=>'in:0,1',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
            'in'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
            'phone.mobile'=>Lib_const_status::MOBILE_FORMAT_ERROR,
        ]);
        if($fromErr){//输出表单验证错误信息

            return $this->response($fromErr);
        }
        $detailed_address = $all['province'].$all['city'].$all['area'].$all['address'];
        $map_data = MapServices::get_lng_lat_tx($detailed_address);
        $response_json = $this->initResponse();
        if(empty($map_data)){
            $response_json->status = Lib_const_status::MAP_ADDRESS_DISCREPANCY;
            return $this->response($response_json);
        }
        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;
        $all['user_id'] = $user_id;
        $all['lng']=$map_data['lng'];
        $all['lat']=$map_data['lat'];
        $this->address->insertAddress($all,$user_id);

        $response_json->code = Lib_const_status::CORRECT;
        $response_json->status = Lib_const_status::SUCCESS;
        return $this->response($response_json);
    }


    /**
     * 更新用户地址
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addressUpdate(Request $request){

        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'address_id'=>'required',
            'province'=>'required',
            'city'=>'required',
            'area'=>'required',
            'address'=>'required',
            'name'=>'required',
            'phone'=>'required|mobile',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
            'phone.mobile'=>Lib_const_status::MOBILE_FORMAT_ERROR,
        ]);
        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }
        $detailed_address = $all['province'].$all['city'].$all['area'].$all['address'];
        $map_data = MapServices::get_lng_lat_tx($detailed_address);
        $response_json = $this->initResponse();
        if(empty($map_data)){
            $response_json->status = Lib_const_status::MAP_ADDRESS_DISCREPANCY;
            return $this->response($response_json);
        }
        $access_entity = AccessEntity::getInstance();
        $address_id = $all['address_id'];
        $all['lng']=$map_data['lng'];
        $all['lat']=$map_data['lat'];
        unset($all['address_id']);
        $user_id = $access_entity->user_id;
        $this->address->updateAddress($all,$user_id,$address_id);
        $response_json->code = Lib_const_status::CORRECT;
        $response_json->status = Lib_const_status::SUCCESS;
        return $this->response($response_json);
    }


    /**
     * 设置 默认地址
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function AddressDefault(Request $request){
        $address_id = $request->input('address_id');
        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;
        $this->address->setDefault($user_id,$address_id);
        $response_json = $this->initResponse();
        $response_json->code = Lib_const_status::CORRECT;
        $response_json->status = Lib_const_status::SUCCESS;
        return $this->response($response_json);
    }
    /**
     * 删除用户收货地址
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addressDel(Request $request){
        $address_id = $request->input('address_id');
        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;
        $int = $this->address->del($user_id,$address_id);
        $response_json = $this->initResponse();
        $response_json->status = Lib_const_status::USER_ADDRESS_NON_EXISTENT;
        if($int == 1){
            $response_json->status = Lib_const_status::SUCCESS;
        }
        return $this->response($response_json);

    }



}