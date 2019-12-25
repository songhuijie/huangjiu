<?php
/**
 * Created by PhpStorm.
 * User: shj
 * Date: 2018/10/10
 * Time: 上午11:21
 */
namespace App\Libraries;

class Lib_config{
    const SEARCH_LIMIT = 10;


    //商品库存添加
    const GOODS_ADD = 1;
    //商品库存失去
    const GOODS_DEL = 2;


    //订单状态
    //'0待支付,1支付成功待发货,2待配送,3已发货,4完成,5退款,6取消',
    const ORDER_STATUS_ZERO =0;//'0待支付
    const ORDER_STATUS_ONE =1;//'1支付成功待发货
    const ORDER_STATUS_TWO =2;//'2待配送
    const ORDER_STATUS_THREE =3;//'3已发货
    const ORDER_STATUS_FOUR =4;//'4完成
    const ORDER_STATUS_FIVE =5;//'5退款
    const ORDER_STATUS_SIX =6;//'6取消


    const PAGE = 1;
    const LIMIT = 5;


    const AGENT_STATUS_YES = 1;
    const AGENT_STATUS_NO  = 0;

}