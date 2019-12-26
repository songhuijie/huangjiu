<?php

namespace App\Http\Controllers\Api;

use App\Libraries\Lib_const_status;
use App\Model\Config;
use App\Http\Controllers\Controller;
use App\Model\WithdrawLog;
use App\Services\AccessEntity;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    private $config;
    private $withdraw;
    public function __construct(WithdrawLog $withdraw,Config $config)
    {
        $this->withdraw = $withdraw;
        $this->config = $config;
    }


    public function Log(Request $request){
        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'amount'=>'required',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息

            return $this->response($fromErr);
        }

        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;
        $withdraw = $this->withdraw->WithdrawList($user_id);
        $response_json = $this->initResponse();
        $response_json->status = Lib_const_status::SUCCESS;
        $response_json->data = $withdraw;
        return $this->response($response_json);
    }


    /**
     *申请提现
     */
    public function withdraw(Request $request){

    }




}