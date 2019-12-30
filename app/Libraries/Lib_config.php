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


    const AGENT_DELIVERY_STATUS_YES = 1;
    const AGENT_SECOND_LEVEL = 2;
    const AGENT_THIRD_LEVEL = 3;
    const AGENT_STATUS_NO  = 0;


    const ADD    = '+';
    const REDUCE = '-';
    const WITHDRAW = 3;

    //顺丰速运	SF
    //百世快递	HTKY
    //中通快递	ZTO
    //申通快递	STO
    //圆通速递	YTO
    //韵达速递	YD
    //邮政快递包裹	YZPY
    //EMS	EMS
    //天天快递	HHTT
    //京东快递	JD
    //优速快递	UC
    //德邦快递	DBL
    //宅急送	ZJS
    const EXPRESS_TYPE = [
        1=>'顺丰速运',
        2=>'百世快递',
        3=>'中通快递',
        4=>'申通快递',
        5=>'圆通速递',
        6=>'韵达速递',
        7=>'邮政快递包裹',
        8=>'EMS',
        9=>'天天快递',
        10=>'京东快递',
        11=>'优速快递',
        12=>'德邦快递',
        13=>'宅急送',
    ];
}