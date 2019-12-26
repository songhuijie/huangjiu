<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/21
 * Time: 11:40
 */
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Libraries\Lib_const_status;
use App\Model\Address;
use App\Model\Agent;
use App\Model\Goods;
use App\Model\IncomeDetails;
use App\Model\Order;
use App\Model\WithdrawLog;
use App\Services\AccessEntity;

class IncomeController extends Controller{


    private $income;
    private $withdraw_log;

    public function __construct(WithdrawLog $withdraw_log,IncomeDetails $income)
    {

        $this->withdraw_log = $withdraw_log;
        $this->income = $income;

    }

    /**
     * 返回收益明细
     */
    public function IncomeList(){

        $response_json = $this->initResponse();
        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;
        $income= $this->income->incomeList($user_id);
        $response_json->status = Lib_const_status::SUCCESS;
        $response_json->data = $income;
        return $this->response($response_json);
    }

    /**
     * 提现记录日志
     */
    public function WithdrawList(){
        $response_json = $this->initResponse();
        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;
        $withdraw = $this->withdraw_log->WithdrawList($user_id);
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
        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;

        dd($user_id);
        $response_json = $this->initResponse();
        $response_json->status = Lib_const_status::SUCCESS;
        return $this->response($response_json);

    }

}