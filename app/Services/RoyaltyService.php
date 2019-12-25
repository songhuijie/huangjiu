<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/19
 * Time: 9:08
 */
namespace App\Services;

use App\Events\IncomeEvent;
use App\Model\Agent;
use App\Model\Asset;
use App\Model\Friend;
use App\Model\IncomeDetails;

class RoyaltyService{




    const  PATTERN_FIRST  = 0;
    const  PATTERN_SECOND = 1;
    const  PATTERN_THIRD  = 2;
    const  PATTERN = [
        0=>[0.85],
        1=>[0.2,0.65],
        2=>[0.15,0.05,0.65]
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
        $friend = new Friend();
        $friend = $friend->GetFriend($user_id);
        if($friend){
            if($agent_id != 0){
                //经销商发货
                self::agentRoyalty($friend,$user_id,$order_royalty_price,$agent_id);
            }else{
                //店铺发货
                self::Royalty($friend,$user_id,$order_royalty_price);
            }
        }
        //2种提成模式  1种is_arrive 1 经销商发货  0 店铺后台发货
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
        $income = new IncomeDetails();
        $agent_info = $agent->find($agent_id);
        if($agent_info){
            $parent_id = $friend->parent_id;//上级
            $parent_parent_id = $friend->parent_parent_id;//上上级
            $best_id = $agent_info->user_id;//最上级
            if($best_id != 0){
                $pattern = self::PATTERN[self::PATTERN_THIRD];
                $parent_contribute_amount = bcmul($order_royalty_price,$pattern[0],2);
                $parent_parent_contribute_amount = bcmul($order_royalty_price,$pattern[1],2);
                $best_contribute_amount = bcmul($order_royalty_price,$pattern[2],2);
                $asset_data = [
                    ['user_id'=>$parent_id,'royalty_balance'=>$parent_contribute_amount],
                    ['user_id'=>$parent_parent_id,'royalty_balance'=>$parent_parent_contribute_amount],
                    ['user_id'=>$best_id,'royalty_balance'=>$best_contribute_amount,'agent'=>1],
                ];
                $friend->updateContribution($user_id,$parent_contribute_amount,$parent_parent_contribute_amount);

            }elseif($best_id == 0 && $parent_parent_id != 0){
                $pattern = self::PATTERN[self::PATTERN_SECOND];
                $parent_contribute_amount = bcmul($order_royalty_price,$pattern[0],2);
                $parent_parent_contribute_amount = bcmul($order_royalty_price,$pattern[1],2);
                $asset_data = [
                    ['user_id'=>$parent_id,'royalty_balance'=>$parent_contribute_amount],
                    ['user_id'=>$parent_parent_id,'royalty_balance'=>$parent_parent_contribute_amount],
                ];
                $friend->updateContribution($user_id,$parent_contribute_amount);
            }else{
                $pattern = self::PATTERN[self::PATTERN_FIRST];
                $parent_contribute_amount = bcmul($order_royalty_price,$pattern[0],2);
                $asset_data = [
                    ['user_id'=>$parent_id,'royalty_balance'=>$parent_contribute_amount],
                ];
                $friend->updateContribution($user_id,$parent_contribute_amount);
            }

            $asset = new Asset();
            $income_data = [];
            $income_time = time();
            foreach($asset_data as $v){
                $asset->updateRoyaltyBalance($v,isset($v['agent'])?1:0);

                $data = [
                    'user_id'=>$v['user_id'],
                    'income_type'=>isset($v['agent'])?2:1,
                    'amount'=>$v['royalty_balance'],
                    'income_time'=>$income_time,
                ];
                $income_data[] = $data;

            }
            event(new IncomeEvent($income_data,$income));
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
        $parent_id = $friend->parent_id;//上级
        $parent_parent_id = $friend->parent_parent_id;//上上级
        $best_id = $friend->best_id;//最上级
        if($best_id != 0){
            $pattern = self::PATTERN[self::PATTERN_THIRD];
            $parent_contribute_amount = bcmul($order_royalty_price,$pattern[0],2);
            $parent_parent_contribute_amount = bcmul($order_royalty_price,$pattern[1],2);
            $best_contribute_amount = bcmul($order_royalty_price,$pattern[2],2);
            $asset_data = [
                ['user_id'=>$parent_id,'royalty_balance'=>$parent_contribute_amount],
                ['user_id'=>$parent_parent_id,'royalty_balance'=>$parent_parent_contribute_amount],
                ['user_id'=>$best_id,'royalty_balance'=>$best_contribute_amount],
            ];
            $friend->updateContribution($user_id,$parent_contribute_amount,$parent_parent_contribute_amount,$best_contribute_amount);

        }elseif($best_id == 0 && $parent_parent_id != 0){
            $pattern = self::PATTERN[self::PATTERN_SECOND];
            $parent_contribute_amount = bcmul($order_royalty_price,$pattern[0],2);
            $parent_parent_contribute_amount = bcmul($order_royalty_price,$pattern[1],2);
            $asset_data = [
                ['user_id'=>$parent_id,'royalty_balance'=>$parent_contribute_amount],
                ['user_id'=>$parent_parent_id,'royalty_balance'=>$parent_parent_contribute_amount],
            ];
            $friend->updateContribution($user_id,$parent_contribute_amount,$parent_parent_contribute_amount);
        }else{
            $pattern = self::PATTERN[self::PATTERN_FIRST];
            $parent_contribute_amount = bcmul($order_royalty_price,$pattern[0],2);
            $asset_data = [
                ['user_id'=>$parent_id,'royalty_balance'=>$parent_contribute_amount],
            ];
            $friend->updateContribution($user_id,$parent_contribute_amount);
        }

        $asset = new Asset();
        $income = new IncomeDetails();
        $income_data = [];
        $income_time = time();
        foreach($asset_data as $v){
            $asset->updateRoyaltyBalance($v);

            $data = [
                'user_id'=>$v['user_id'],
                'income_type'=>isset($v['agent'])?2:1,
                'amount'=>$v['royalty_balance'],
                'income_time'=>$income_time,
            ];
            $income_data[] = $data;
        }
        event(new IncomeEvent($income_data,$income));
    }
}