<?php

namespace App\Http\Controllers\Api;

use App\Libraries\Lib_const_status;
use App\Model\Config;
use App\Http\Controllers\Controller;
use App\Model\WithdrawLog;
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
    }


    /**
     *
     */
    public function withdraw(){

    }




}