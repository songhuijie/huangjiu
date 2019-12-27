<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/27
 * Time: 11:10
 */
namespace App\Services;

use App\Events\IncomeEvent;
use App\Libraries\Lib_const_status;
use App\Model\Asset;
use App\Model\IncomeDetails;
use Illuminate\Support\Facades\DB;

class AssetService{

    const REDUCE = 2;
    const ADD    = 1;

    /**
     * 处理 用户金额问题
     * @param $user_id
     * @param $amount
     * @param $symbol
     * @param $type
     * @return int|mixed
     */
    public static function HandleBalance($user_id,$amount,$symbol,$type){

        $asset = new Asset();
        $income = new IncomeDetails();

        $balance = $asset->getBalance($user_id);
        $surplus_amount = $balance;

        if($symbol == '-'){

            if((int)bcmul($surplus_amount,100) > (int)bcmul($amount,100)){
                $int = $asset->updateRoyaltyBalance($user_id,$amount,self::REDUCE);
            }else{
                $int = Lib_const_status::USER_BALANCE_NOT_ENOUGH;
            }

        }else{
               $int = $asset->updateRoyaltyBalance($user_id,$amount,self::ADD);
        }
        if($int == 1){
            $income_time = time();
            $data = [
                'user_id'=>$user_id,
                'income_type'=>$type,
                'amount'=>$amount,
                'income_time'=>$income_time,
            ];
            $income_data[] = $data;
            event(new IncomeEvent($income_data,$income));
        }


        return $int == 0 ? Lib_const_status::OTHER_ERROR:$int;


    }
}