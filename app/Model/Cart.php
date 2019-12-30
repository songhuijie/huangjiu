<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cart extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'cart';
    public $timestamps = false;
    protected $dateFormat = 'U';//使用时间戳方式添加
    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    // public $timestamps = false;
    protected $fillable = [
        'sku_id','cart_num','sku_name','sku_price'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        ''
    ];

    protected $select = ['id','user_id','goods_id as goods_type','sku_id','cart_num','sku_name','sku_price'];
    /**
     * 获取购物车列表
     * @param $user_id
     * @return mixed
     */
    public function getCart($user_id){
        return $this->select($this->select)->where(['user_id'=>$user_id])->get();
    }

    public function getCarts($user_id){
        $data = $this->where('user_id', $user_id)
            ->select('cart.*')
            ->leftJoin('goods', 'goods.id', '=', 'cart.sku_id')
            ->with(['goods'])
            ->get();
        return $data;
    }

    /**
     * 自定义插入数据
     * @param $data
     * @return mixed
     */
    public function CartInsert($data){

        $id = $this->where(['user_id'=>$data['user_id'],'sku_id'=>$data['sku_id']])->value('id');
        if($id){
            $num = $data['cart_num'];
            return $this->where('id',$id)->update(['cart_num'=>DB::raw("cart_num + $num")]);
        }else{
            return $this->insert($data);
        }

    }

    /**
     * 更新商品购物车数量
     * @param $data
     * @param $user_id
     * @return mixed
     */
    public function updateCartNum($data,$user_id){
        return $this->where(['id'=>$data['cart_id'],'user_id'=>$user_id])->update(['cart_num'=>$data['cart_num']]);
    }

    public function updateCartByPay($user_id,$goods_id,$num){
        $cart_num = $this->where(['user_id'=>$user_id,'goods_id'=>$goods_id])->value('cart_num');
        if($cart_num){
            if($num >= $cart_num){
                $this->where(['user_id'=>$user_id,'goods_id'=>$goods_id])->delete();
            }else{
                $this->where(['user_id'=>$user_id,'goods_id'=>$goods_id])->update(['cart_num'=>DB::raw("cart_num - $num")]);
            }
        }

    }
    public function updateCart($user_id,$goods_id,$cart_num){

    }


    public function img(){
        return $this->hasOne(Goods::class,'goods_id','sku_id');
    }


    public function getGoods(){
        return $this->hasOne(Cart::class,'id','sku_id');
    }


}