<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/19
 * Time: 9:08
 */
namespace App\Services;

use App\Model\Agent;
use App\Model\Asset;
use App\Model\Friend;

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

            }elseif($best_id == 0 && $parent_parent_id != 0){
                $pattern = self::PATTERN[self::PATTERN_SECOND];
                $parent_contribute_amount = bcmul($order_royalty_price,$pattern[0],2);
                $parent_parent_contribute_amount = bcmul($order_royalty_price,$pattern[1],2);
                $asset_data = [
                    ['user_id'=>$parent_id,'royalty_balance'=>$parent_contribute_amount],
                    ['user_id'=>$parent_parent_id,'royalty_balance'=>$parent_parent_contribute_amount],
                ];
            }else{
                $pattern = self::PATTERN[self::PATTERN_FIRST];
                $parent_contribute_amount = bcmul($order_royalty_price,$pattern[0],2);
                $asset_data = [
                    ['user_id'=>$parent_id,'royalty_balance'=>$parent_contribute_amount],
                ];
            }

            $asset = new Asset();
            foreach($asset_data as $v){
                $asset->updateRoyaltyBalance($v);
            }

        }

        //2种提成模式  1种is_arrive 1 经销商发货  0 店铺后台发货
        return 1;
    }
}