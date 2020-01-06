<?php

namespace App\Http\Controllers\Api;

use App\Libraries\Lib_const_status;
use App\Model\Asset;
use App\Model\Config;
use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Model\Reply;
use App\Model\User;
use App\Model\WithdrawLog;
use App\Services\AccessEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReplyController extends Controller
{
    private $user;
    private $asset;
    private $config;
    private $order;
    private $replay;
    public function __construct(Order $order,Asset $asset,Config $config,User $user,Reply $replay)
    {
        $this->user = $user;
        $this->asset = $asset;
        $this->order = $order;
        $this->config = $config;
        $this->replay = $replay;
    }


    public function Relay(Request $request){
        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'order_id'=>'required',
            'goods_id'=>'required',
            'detail'=>'required',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息

            return $this->response($fromErr);
        }
        $response_json = $this->initResponse();


        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;

        $order = $this->order->find($all['order_id']);
        if($order){
            $goods_detail =  $order->goods_detail;
            $goods_ids = array_column($goods_detail,'good_title','goods_id');
            if(isset($goods_ids[$all['goods_id']])){
                $all['user_id'] = $user_id;
                $this->replay->insertReply($all);
            }
        }

        $response_json->status = Lib_const_status::SUCCESS;
        return $this->response($response_json);

    }





}