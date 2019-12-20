<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/20
 * Time: 15:23
 */
namespace  App\Services;

use App\Model\Goods;

class GoodsService{


    /**
     * 商品更新库存
     * @param $goods_id 商品ID
     * @param $type 1表示增加库存 2表示失去库存
     * @param $num  失去或增加的数量
     * @return mixed
     */
    public static function UpdateStock($goods_id,$type,$num){

        $goods = new Goods();
        return $goods->updateStock($goods_id,$type,$num);

    }
}