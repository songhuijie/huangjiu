<?php

namespace App\Http\Controllers\Api;

use App\Libraries\Lib_const_status;
use App\Model\Asset;
use App\Model\Config;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\WithdrawLog;
use App\Services\AccessEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WithdrawController extends Controller
{
    private $user;
    private $asset;
    private $config;
    private $withdraw;
    public function __construct(WithdrawLog $withdraw,Asset $asset,Config $config,User $user)
    {
        $this->user = $user;
        $this->asset = $asset;
        $this->withdraw = $withdraw;
        $this->config = $config;
    }


    /**
     * 获取提现日志
     * @return \Illuminate\Http\JsonResponse
     */
    public function Log(){
        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;
        $withdraw = $this->withdraw->WithdrawList($user_id);
        $response_json = $this->initResponse();
        $response_json->status = Lib_const_status::SUCCESS;
        $response_json->data = $withdraw;
        return $this->response($response_json);
    }


    /**
     * 申请提现
     */
    public function withdraw(Request $request){
        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'amount'=>'required',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息

            return $this->response($fromErr);
        }
        $response_json = $this->initResponse();


        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;
        $amount = $all['amount'];
        $balance= $this->asset->getBalance($user_id);
        if($balance > $amount){
            $withdraw_time = time();
            $withdraw_data = [
                'user_id'=>$user_id,
                'withdraw_type'=>1,
                'amount'=>$amount,
                'surplus_amount'=>'',
                'withdraw_time'=>$withdraw_time,
                'status'=>0,
            ];
            $this->withdraw->insert($withdraw_data);
            $response_json->status = Lib_const_status::SUCCESS;
        }else{
            $response_json->status = Lib_const_status::USER_BALANCE_NOT_ENOUGH;
        }

        return $this->response($response_json);
    }




}