<?php
/**
 * Created by PhpStorm.
 * User: shj
 * Date: 2018/10/10
 * Time: 上午11:21
 */
namespace App\Libraries;

class Lib_const_status{
    const CORRECT = 0;
    const SUCCESS = 0;
    const SERVICE_ERROR = 1;
    //请求必要参数为空或者格式错误
    const ERROR_REQUEST_PARAMETER = 10;
    //请求过多,暂时被限制
    const ERROR_TOO_MUCH_REQUEST = 15;
    //用户登录成功
    const USER_LANDING_SUCCESSFULLY = 10000;
    //用户token失效
    const USER_TOKEN_FAILURE = 10001;
    //用户不存在
    const USER_NOT_EXISTENT = 10002;
    //用户地址不存在
    const USER_ADDRESS_NON_EXISTENT = 10010;

    //用户已申请过代理
    const USER_AGENT_ALREADY_APPLY = 10011;
    //改区域代理已被申请
    const REGION_AGENT_ALREADY_APPLY = 10012;
    //用户代理不存在
    const USER_AGENT_NOT_EXISTENT = 10013;
    //用户未成为代理商
    const USER_NOT_AGENT = 10014;
    //用户代理已达到上限
    const USER_AGENT_ALREADY_UPPER = 10015;

    //推荐文章不存在
    const RECOMMENDED_ARTICLES_NOT_EXISTENT  = 19000;
    //手机号格式错误
    const MOBILE_FORMAT_ERROR = 20000;


    //地址不存在 或者不符合规则
    const MAP_ADDRESS_DISCREPANCY = 30000;


    //商品不存在
    const GOODS_NOT_EXISTENT  = 40000;
    //商品库存不足
    const GOODS_NOT_ENOUGH_STOCK = 40001;
    //商品已被收藏过
    const GOODS_HAS_BEEN_COLLECTED = 40002;
    //已取消过收藏
    const GOODS_COLLECTION_CANCELLED = 40003;

    //购物 商品数量不足 不能更新
    const CART_GOODS_NOT_ENOUGH  = 40003;
    //购物商品不存在
    const CART_GOODS_NOT_EXISTENT  = 40004;
    //购物商品已被删除或不存在
    const CART_GOODS_DELETED  = 40005;

    //下单失败
    const  ORDER_PLACE_FAIL  = 50000;
    //订单不存在
    const  ORDER_NOT_EXISTENT  = 50001;
    //订单待支付
    const  ORDER_TO_BE_PAID  = 50002;
    //订单待发货
    const  ORDER_TO_BE_SHIPPED  = 50003;
    //订单已确认收货
    const  ORDER_RECEIVED_GOODS  = 50004;
    //订单待配送
    const  ORDER_TO_BE_DELIVERED  = 50005;
    //订单已被取消或者其他原因
    const  ORDER_HAS_BEEN_CANCELLED  = 50006;



}