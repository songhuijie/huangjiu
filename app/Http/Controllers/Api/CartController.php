<?php

namespace App\Http\Controllers\Api;

use App\Libraries\Lib_config;
use App\Libraries\Lib_const_status;
use App\Model\Cart;
use App\Model\Goods;
use App\Model\GoodsType;
use App\Services\AccessEntity;
use App\Services\GoodsService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\HotSearch;


class CartController extends Controller
{
    private $user;
    private $good;
    private $good_type;
    private $cart;
    public function __construct(User $user,Goods $good,GoodsType $good_type,Cart $cart)
    {
        $this->user = $user;
        $this->good = $good;
        $this->good_type = $good_type;
        $this->cart = $cart;
    }

    /**
     * 购物车列表
     */
    public function CartList()
    {

        $response_json = $this->initResponse();
        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;

        $cart_list = $this->cart->getCart($user_id);

        foreach($cart_list as $k=>$v){

            $cart_list[$k]->goods_info = $this->good->getGoodsBySkuId($v->sku_id);
        }

        $response_json->status = Lib_const_status::SUCCESS;
        $response_json->data = $cart_list;
        return $this->response($response_json);

    }

    /**
     * 购物车添加
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function CartAdd(Request $request){
        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'sku_id'=>'required|int',
            'cart_num'=>'required|int',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
            'int'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }
        $response_json = $this->initResponse();



        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;
        $good = $this->good->find($all['sku_id']);

        if($good){
            $all['user_id'] = $user_id;
            $all['goods_id'] = $good->good_type;
            $all['sku_name'] = $good->good_title;
            $all['sku_price'] = $good->new_price;

            $cart = $this->cart->CartInsert($all);
            if($cart){
                $response_json->status = Lib_const_status::SUCCESS;
            }
            return $this->response($response_json);
        }
        $response_json->status = Lib_const_status::GOODS_NOT_EXISTENT;
        return $this->response($response_json);
    }

    /**
     * 购物车修改
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function CartEdit(Request $request){

        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'cart_id'=>'required|int',
            'cart_num'=>'required|int',
            'type'=>'required|in:1,2',//1 加 2减
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
            'in'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
            'int'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }

        $response_json = $this->initResponse();
        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;

        $cart = $this->cart->find($all['cart_id']);
        if($cart){


            if($all['type'] == 2){
                $all['cart_num'] = $cart->cart_num  - $all['cart_num'];
                if($all['cart_num'] < 0){
                    $response_json->status = Lib_const_status::CART_GOODS_NOT_ENOUGH;
                    return $this->response($response_json);
                }
            }else{
                $all['cart_num'] = $cart->cart_num  + $all['cart_num'];
            }
            $detail = $this->cart->updateCartNum($all,$user_id);
            $response_json->status = Lib_const_status::SUCCESS;

        }else{
            $response_json->status = Lib_const_status::CART_GOODS_NOT_EXISTENT;
        }
        return $this->response($response_json);
    }

    /**
     *  购物车删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function CartDel(Request $request){

        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'cart_id'=>'required',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }


        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;
        $response_json = $this->initResponse();

        $detail = $this->cart->where(['user_id'=>$user_id,'id'=>$all['cart_id']])->delete();
        if($detail){
            $response_json->status = Lib_const_status::SUCCESS;
        }else{
            $response_json->status = Lib_const_status::CART_GOODS_DELETED;
        }
        return $this->response($response_json);
    }


}