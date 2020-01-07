<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/19
 * Time: 9:08
 */
namespace App\Services;

use App\Events\IncomeEvent;
use App\Libraries\Lib_config;
use App\Model\Agent;
use App\Model\Asset;
use App\Model\Friend;
use App\Model\FriendShip;
use App\Model\IncomeDetails;
use Illuminate\Support\Facades\Log;

class RoyaltyService{




    const  PATTERN_FIRST  = 0;
    const  PATTERN_SECOND = 1;
    const  PATTERN_THIRD  = 2;
    const  PATTERN_FOUR  = 3;
    const  PATTERN = [
        //原
//        0=>[0.85],//总代理
//        1=>[0.2,0.65],//一级代理  总代理
//        2=>[0.15,0.05,0.65],//二级代理  一级代理 总代理
//        3=>[0.2,0.8],//一级代理和 其他代理商发货

        //改成
        //佣金分配，按照商品金额全额进行分配（不含快递费），
        //总代理始终80%，一级的普通客户消费一级得20%，
        //二级或二级的普通客户消费一级得5%、二级得15%。
        //若A区客户在B区消费则由B区总代得80%，A区的一二级代理佣金不变。

        0=>[0.80],//总代理
        1=>[0.2,0.80],//一级代理  总代理
        2=>[0.15,0.05,0.80],//二级代理  一级代理 总代理
        3=>[0.2,0.8]//一级代理和 其他代理商发货
    ];

    /**
     * 处理 商品提成
     * @param $user_id
     * @param $order_royalty_price
     * @param $is_arrive
     * @param $agent_id
     * @return int
     */
    public static function HandleRoyalty($user_id,$order_royalty_price,$is_arrive,$agent_id){
        $friend = new FriendShip();
        $friend = $friend->getByUser($user_id);//获取好友初始关系
        if($friend){
            if($agent_id != 0){
                //经销商发货
                self::agentRoyalty($friend,$user_id,$order_royalty_price,$agent_id);
            }else{
                //店铺发货
                self::Royalty($friend,$user_id,$order_royalty_price);
            }
        }else{
            if($agent_id != 0){
                //普通用户情况下  买到商品如果是代理商发货给代理商收益
                self::ordinary($user_id,$order_royalty_price,$agent_id);
            }
        }
        //2种提成模式  1种is_arrive 1 经销商发货  0 店铺后台发货
    }

    /**
     * 普通用户 代理商发货情况下
     * @param $user_id
     * @param $order_royalty_price
     * @param $agent_id
     */
    public static function ordinary($user_id,$order_royalty_price,$agent_id){

        $agent = new Agent();
        $agent = $agent->find($agent_id);
        $asset_data = [];
        if($agent){
            $agent_user_id = $agent->user_id;
            $pattern = self::PATTERN[self::PATTERN_FIRST];
            $parent_contribute_amount = bcmul($order_royalty_price,$pattern[0],2);
            $asset_data = [
                ['user_id'=>$agent_user_id,'royalty_balance'=>$parent_contribute_amount,'proportion'=>$pattern[0],'contribution_id'=>$user_id,'agent'=>1],
            ];
        }

        if($asset_data){
            self::AssetIncome($asset_data);
        }

    }


    /**
     * 代理商提成
     * @param $friend
     * @param $user_id
     * @param $order_royalty_price
     * @param $agent_id
     */
    public static function agentRoyalty($friend,$user_id,$order_royalty_price,$agent_id){
        //代理商发货的情况
        $agent = new Agent();
        $agent_info = $agent->find($agent_id);
        if($agent_info){

            $best_id = $friend->best_id;//最上级
            $agent_user_id = $agent_info->user_id;//代理ID

            $status = $friend->status;//设置的几级用户

            $friend_ship = new FriendShip();

            $result = $friend_ship->getByUser($user_id);

            $asset_data = [];

            switch ($status){
                //处理 1级情况
                case 0:
                    $parent_id = 0;
                    $parent_parent_id = 0;

                    if($result && !empty($result->ship)){
                        $data = explode(',',$result->ship);
                        foreach($data as $v){
                            $result = $friend_ship->getByStatus($v,2);
                            if($result){
                                $parent_id =  $v;
                                break;
                            }
                        }
                        foreach($data as $v){
                            $result = $friend_ship->getByStatus($v,3);
                            if($result){
                                $parent_parent_id =  $v;
                                break;
                            }
                        }
                    }


                    if($parent_id != 0 && $parent_parent_id != 0){
                        $pattern = self::PATTERN[self::PATTERN_THIRD];
                        $parent_contribute_amount_new = bcmul($order_royalty_price,$pattern[0],2);
                        $parent_parent_contribute_amount_new = bcmul($order_royalty_price,$pattern[1],2);
                        $best_contribute_amount_new = bcmul($order_royalty_price,$pattern[2],2);

                        $asset_data = [
                            ['user_id'=>$parent_parent_id,'royalty_balance'=>$parent_contribute_amount_new,'contribution_id'=>$user_id,'proportion'=>$pattern[0]],
                            ['user_id'=>$parent_id,'royalty_balance'=>$parent_parent_contribute_amount_new,'contribution_id'=>$user_id,'proportion'=>$pattern[1]],
                            ['user_id'=>$agent_user_id,'royalty_balance'=>$best_contribute_amount_new,'contribution_id'=>$user_id,'proportion'=>$pattern[2],'agent'=>1],
                        ];
                    }elseif($parent_id != 0 || $parent_parent_id != 0){

                        if($parent_id != 0){
                            $parent_parent_id = $parent_id;
                        }
                        $pattern = self::PATTERN[self::PATTERN_SECOND];
                        $parent_contribute_amount_new = bcmul($order_royalty_price,$pattern[0],2);
                        $best_contribute_amount_new = bcmul($order_royalty_price,$pattern[1],2);
                        $asset_data = [
                            ['user_id'=>$parent_parent_id,'royalty_balance'=>$parent_contribute_amount_new,'contribution_id'=>$user_id,'proportion'=>$pattern[0]],
                            ['user_id'=>$agent_user_id,'royalty_balance'=>$best_contribute_amount_new,'contribution_id'=>$user_id,'proportion'=>$pattern[1],'agent'=>1],
                        ];
                    }else{
                        $pattern = self::PATTERN[self::PATTERN_FOUR];
                        $parent_contribute_amount_new = bcmul($order_royalty_price,$pattern[0],2);
                        $best_contribute_amount_new = bcmul($order_royalty_price,$pattern[1],2);
                        $asset_data = [
                            ['user_id'=>$best_id,'royalty_balance'=>$parent_contribute_amount_new,'contribution_id'=>$user_id,'proportion'=>$pattern[0]],
                            ['user_id'=>$agent_user_id,'royalty_balance'=>$best_contribute_amount_new,'contribution_id'=>$user_id,'proportion'=>$pattern[1],'agent'=>1],
                        ];
                    }

                    break;
                //处理当时3级代理
                case 3:
                    $parent_parent_id = 0;

                    if($result && !empty($result->ship)){
                        $data = explode(',',$result->ship);
                        foreach($data as $v){
                            $result = $friend_ship->getByStatus($v,2);
                            if($result){
                                $parent_parent_id =  $v;
                                break;
                            }
                        }
                    }
                    if($parent_parent_id == 0){
                        $parent_parent_id = $best_id;
                    }
                    $pattern = self::PATTERN[self::PATTERN_THIRD];
                    $parent_contribute_amount_new = bcmul($order_royalty_price,$pattern[0],2);
                    $parent_parent_contribute_amount_new = bcmul($order_royalty_price,$pattern[1],2);
                    $best_contribute_amount_new = bcmul($order_royalty_price,$pattern[2],2);
                    $asset_data = [
                        ['user_id'=>$user_id,'royalty_balance'=>$parent_contribute_amount_new,'contribution_id'=>$user_id,'proportion'=>$pattern[0]],
                        ['user_id'=>$parent_parent_id,'royalty_balance'=>$parent_parent_contribute_amount_new,'contribution_id'=>$user_id,'proportion'=>$pattern[1]],
                        ['user_id'=>$agent_user_id,'royalty_balance'=>$best_contribute_amount_new,'contribution_id'=>$user_id,'proportion'=>$pattern[2],'agent'=>1]
                    ];

                    break;
                //处理当时2级用户
                case 2:
                    $pattern = self::PATTERN[self::PATTERN_SECOND];
                    $parent_contribute_amount_new = bcmul($order_royalty_price,$pattern[0],2);
                    $best_contribute_amount_new = bcmul($order_royalty_price,$pattern[1],2);
                    $asset_data = [
                        ['user_id'=>$user_id,'royalty_balance'=>$parent_contribute_amount_new,'contribution_id'=>$user_id,'proportion'=>$pattern[0]],
                        ['user_id'=>$agent_user_id,'royalty_balance'=>$best_contribute_amount_new,'contribution_id'=>$user_id,'proportion'=>$pattern[1],'agent'=>1]
                    ];
            }


            if($asset_data){
                self::AssetIncome($asset_data);
            }

        }

    }

    /**
     * 非代理商提成
     * @param $friend
     * @param $user_id
     * @param $order_royalty_price
     */
    public static function Royalty($friend,$user_id,$order_royalty_price){
        //非代理商发货的情况
        $best_id = $friend->best_id;//上级
        $status = $friend->status;//设置的几级用户
        $friend_ship = new FriendShip();
        $asset_data = [];

        $result = $friend_ship->getByUser($user_id);
        switch ($status){
            case 0:
                $parent_id = 0;
                $parent_parent_id = 0;

                if($result && !empty($result->ship)){
                    $data = explode(',',$result->ship);
                    foreach($data as $v){
                        $result = $friend_ship->getByStatus($v,2);
                        if($result){
                            $parent_id =  $v;
                            break;
                        }
                    }
                    foreach($data as $v){
                        $result = $friend_ship->getByStatus($v,3);
                        if($result){
                            $parent_parent_id =  $v;
                            break;
                        }
                    }
                }


                if($parent_id != 0 && $parent_parent_id != 0){
                    $pattern = self::PATTERN[self::PATTERN_THIRD];
                    $parent_contribute_amount_new = bcmul($order_royalty_price,$pattern[0],2);
                    $parent_parent_contribute_amount_new = bcmul($order_royalty_price,$pattern[1],2);
                    $best_contribute_amount_new = bcmul($order_royalty_price,$pattern[2],2);

                    $asset_data = [
                        ['user_id'=>$parent_parent_id,'royalty_balance'=>$parent_contribute_amount_new,'contribution_id'=>$user_id,'proportion'=>$pattern[0]],
                        ['user_id'=>$parent_id,'royalty_balance'=>$parent_parent_contribute_amount_new,'contribution_id'=>$user_id,'proportion'=>$pattern[1]],
                        ['user_id'=>$best_id,'royalty_balance'=>$best_contribute_amount_new,'contribution_id'=>$user_id,'proportion'=>$pattern[2]],
                    ];
                }elseif($parent_id != 0 || $parent_parent_id != 0){

                    if($parent_id != 0){
                        $parent_parent_id = $parent_id;
                    }
                    $pattern = self::PATTERN[self::PATTERN_SECOND];
                    $parent_contribute_amount_new = bcmul($order_royalty_price,$pattern[0],2);
                    $best_contribute_amount_new = bcmul($order_royalty_price,$pattern[1],2);
                    $asset_data = [
                        ['user_id'=>$parent_parent_id,'royalty_balance'=>$parent_contribute_amount_new,'contribution_id'=>$user_id,'proportion'=>$pattern[0]],
                        ['user_id'=>$best_id,'royalty_balance'=>$best_contribute_amount_new,'contribution_id'=>$user_id,'proportion'=>$pattern[1]],
                    ];
                }else{
                    $pattern = self::PATTERN[self::PATTERN_FIRST];
                    $best_contribute_amount_new = bcmul($order_royalty_price,$pattern[0],2);
                    $asset_data = [
                        ['user_id'=>$best_id,'royalty_balance'=>$best_contribute_amount_new,'contribution_id'=>$user_id,'proportion'=>$pattern[0]],
                    ];
                }
                break;
            case 2:
                $pattern = self::PATTERN[self::PATTERN_SECOND];
                $parent_contribute_amount_new = bcmul($order_royalty_price,$pattern[0],2);
                $best_contribute_amount_new = bcmul($order_royalty_price,$pattern[1],2);
                $asset_data = [
                    ['user_id'=>$user_id,'royalty_balance'=>$parent_contribute_amount_new,'contribution_id'=>$user_id,'proportion'=>$pattern[0]],
                    ['user_id'=>$best_id,'royalty_balance'=>$best_contribute_amount_new,'contribution_id'=>$user_id,'proportion'=>$pattern[1]],
                ];
                break;
            case 3:
                $parent_parent_id = 0;
                if($result && !empty($result->ship)){
                    $data = explode(',',$result->ship);
                    foreach($data as $v){
                        $result = $friend_ship->getByStatus($v,2);
                        if($result){
                            $parent_parent_id =  $v;
                            break;
                        }
                    }
                }

                if($parent_parent_id == 0){
                    $parent_parent_id = $best_id;
                }
                $pattern = self::PATTERN[self::PATTERN_THIRD];
                $parent_contribute_amount_new = bcmul($order_royalty_price,$pattern[0],2);
                $parent_parent_contribute_amount_new = bcmul($order_royalty_price,$pattern[1],2);
                $best_contribute_amount_new = bcmul($order_royalty_price,$pattern[2],2);
                $asset_data = [
                    ['user_id'=>$user_id,'royalty_balance'=>$parent_contribute_amount_new,'contribution_id'=>$user_id,'proportion'=>$pattern[0]],
                    ['user_id'=>$parent_parent_id,'royalty_balance'=>$parent_parent_contribute_amount_new,'contribution_id'=>$user_id,'proportion'=>$pattern[1]],
                    ['user_id'=>$best_id,'royalty_balance'=>$best_contribute_amount_new,'contribution_id'=>$user_id,'proportion'=>$pattern[2]],
                ];

                break;
        }

        if($asset_data){
            self::AssetIncome($asset_data);
        }


    }


    /**
     * 用户资金变化和 日志记录
     * @param $asset_data
     */
    public static function AssetIncome($asset_data){

        $symbol = Lib_config::ADD;
        foreach($asset_data as $v) {
            $type = isset($v['agent'])?2:1;
            AssetService::HandleBalance($v['user_id'],$v['royalty_balance'],$symbol,$type,$v['proportion'],$v['contribution_id']);
        }
    }
}