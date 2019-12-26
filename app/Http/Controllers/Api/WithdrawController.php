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
        $openid = 1;
        $amount = $all['amount'];
        $config = $this->config->getConfig();
        $appid = $config->appid;
        $mchid = $config->mch_id;
        $mch_secret = $config->mch_secret;
        $key_pem = $config->key_pem;
        $cert_pem = $config->cert_pem;
        $desc = '转账';
        $partner_trade_no = '转账';

        //企业给用户转账
        transferAccounts($appid,$mchid,$openid,$desc,$partner_trade_no,$amount,$mch_secret,$key_pem,$cert_pem);
    }




}