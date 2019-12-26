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
use Illuminate\Support\Facades\Log;

class RoyaltyService{




    const  PATTERN_FIRST  = 0;
    const  PATTERN_SECOND = 1;
    const  PATTERN_THIRD  = 2;
    const  PATTERN_FOUR  = 3;
    const  PATTERN = [
        0=>[0.85],
        1=>[0.2,0.65],
        2=>[0.15,0.05,0.65],
        3=>[0.2,0.8]
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
                ['user_id'=>$agent_user_id,'royalty_balance'=>$parent_contribute_amount,'agent'=>1],
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
            $parent_id = $friend->parent_id;//上级
            $parent_parent_id = $friend->parent_parent_id;//上上级
            $best_id = $friend->best_id;//最上级
            $agent_user_id = $agent_info->user_id;//代理ID

            $status = $friend->status;//设置的几级用户
            $is_delivery = $friend->is_delivery;//设置是否发货

            $asset_data = [];
            switch ($status){
                //处理 1级情况
                case 0:
                    if($parent_id == $agent_user_id){
                        $pattern = self::PATTERN[self::PATTERN_FIRST];
                        $parent_contribute_amount = bcmul($order_royalty_price,$pattern[0],2);
                        $asset_data = [
                            ['user_id'=>$agent_user_id,'royalty_balance'=>$parent_contribute_amount],
                        ];
                        $friend->updateContribution($user_id,$parent_contribute_amount);
                    }else{
                        $pattern = self::PATTERN[self::PATTERN_FIRST];
                        $parent_contribute_amount = bcmul($order_royalty_price,$pattern[0],2);
                        $asset_data = [
                            ['user_id'=>$agent_user_id,'royalty_balance'=>$parent_contribute_amount,'agent'=>1],
                        ];
                    }
                    break;
                //处理当时2级用户
                case 2:

                    if($parent_parent_id == $agent_user_id){

                        $pattern = self::PATTERN[self::PATTERN_SECOND];
                        $parent_contribute_amount = bcmul($order_royalty_price,$pattern[0],2);
                        $parent_parent_contribute_amount = bcmul($order_royalty_price,$pattern[1],2);
                        $asset_data = [
                            ['user_id'=>$parent_id,'royalty_balance'=>$parent_contribute_amount],
                            ['user_id'=>$agent_user_id,'royalty_balance'=>$parent_parent_contribute_amount],
                        ];

                        $friend->updateContribution($user_id,$parent_contribute_amount,$parent_parent_contribute_amount);
                    }else{
                        //处理当时有100% 区分级用户
                        $pattern = self::PATTERN[self::PATTERN_FOUR];
                        $parent_contribute_amount = bcmul($order_royalty_price,$pattern[0],2);
                        $parent_parent_contribute_amount = bcmul($order_royalty_price,$pattern[1],2);
                        $asset_data = [
                            ['user_id'=>$parent_id,'royalty_balance'=>$parent_contribute_amount],
                            ['user_id'=>$agent_user_id,'royalty_balance'=>$parent_parent_contribute_amount,'agent'=>1],
                        ];
                        $friend->updateContribution($user_id,$parent_contribute_amount);
                    }
                    break;
                case 3:
                    if($best_id == $agent_user_id){
                        Log::info('3级相等');
                        $pattern = self::PATTERN[self::PATTERN_THIRD];
                        $parent_contribute_amount = bcmul($order_royalty_price,$pattern[0],2);
                        $parent_parent_contribute_amount = bcmul($order_royalty_price,$pattern[1],2);
                        $best_contribute_amount = bcmul($order_royalty_price,$pattern[2],2);
                        $asset_data = [
                            ['user_id'=>$parent_id,'royalty_balance'=>$parent_contribute_amount],
                            ['user_id'=>$parent_parent_id,'royalty_balance'=>$parent_parent_contribute_amount],
                            ['user_id'=>$agent_user_id,'royalty_balance'=>$best_contribute_amount],
                        ];
                        $friend->updateContribution($user_id,$parent_contribute_amount,$parent_parent_contribute_amount,$best_contribute_amount);
                    }else{
                        Log::info('3级不相等');
                        $pattern = self::PATTERN[self::PATTERN_THIRD];
                        $parent_contribute_amount = bcmul($order_royalty_price,$pattern[0],2);
                        $parent_parent_contribute_amount = bcmul($order_royalty_price,$pattern[1],2);
                        $best_contribute_amount = bcmul($order_royalty_price,$pattern[2],2);
                        $asset_data = [
                            ['user_id'=>$parent_id,'royalty_balance'=>$parent_contribute_amount],
                            ['user_id'=>$parent_parent_id,'royalty_balance'=>$parent_parent_contribute_amount],
                            ['user_id'=>$agent_user_id,'royalty_balance'=>$best_contribute_amount,'agent'=>1],
                        ];
                        $friend->updateContribution($user_id,$parent_contribute_amount,$parent_parent_contribute_amount);
                    }
                    break;
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
        $parent_id = $friend->parent_id;//上级
        $parent_parent_id = $friend->parent_parent_id;//上上级
        $best_id = $friend->best_id;//最上级

        $status = $friend->status;//设置的几级用户
        $asset_data = [];
        switch ($status){
            case 0:
                $pattern = self::PATTERN[self::PATTERN_FIRST];
                $parent_contribute_amount = bcmul($order_royalty_price,$pattern[0],2);
                $asset_data = [
                    ['user_id'=>$parent_id,'royalty_balance'=>$parent_contribute_amount],
                ];
                $friend->updateContribution($user_id,$parent_contribute_amount);
                break;
            case 2:
                $pattern = self::PATTERN[self::PATTERN_SECOND];
                if($best_id != 0){
                    $parent_id = $parent_parent_id;
                    $parent_parent_id = $best_id;
                }
                $parent_contribute_amount = bcmul($order_royalty_price,$pattern[0],2);
                $parent_parent_contribute_amount = bcmul($order_royalty_price,$pattern[1],2);
                $asset_data = [
                    ['user_id'=>$parent_id,'royalty_balance'=>$parent_contribute_amount],
                    ['user_id'=>$parent_parent_id,'royalty_balance'=>$parent_parent_contribute_amount],
                ];
                if($best_id != 0){
                    $friend->updateContribution($user_id,0,$parent_contribute_amount,$parent_parent_contribute_amount);
                }else{
                    $friend->updateContribution($user_id,$parent_contribute_amount,$parent_parent_contribute_amount);
                }

                break;
            case 3:
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