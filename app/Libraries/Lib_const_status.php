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
    //用户地址不存在
    const USER_ADDRESS_NON_EXISTENT = 10010;

    //手机号格式错误
    const MOBILE_FORMAT_ERROR = 20000;


    //地址不存在 或者不符合规则
    const MAP_ADDRESS_DISCREPANCY = 30000;


    //商品不存在
    const GOODS_NOT_EXISTENT  = 40000;
    //商品库存不足
    const GOODS_NOT_ENOUGH_STOCK = 40001;
    //购物 商品数量不足 不能更新
    const CART_GOODS_NOT_ENOUGH  = 40002;
    //购物商品不存在
    const CART_GOODS_NOT_EXISTENT  = 40003;
    //购物商品已被删除或不存在
    const CART_GOODS_DELETED  = 40004;

    //推荐文章不存在
    const RECOMMENDED_ARTICLES_NOT_EXISTENT  = 50000;

}