<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Goods extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'goods';
    public $timestamps = true;
    protected $dateFormat = 'U';//使用时间戳方式添加
    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    // public $timestamps = false;
    protected $fillable = [

    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        ''
    ];

    /**
     * 根据商品类型查询条件
     * @param $good_type
     * @return mixed
     */
    public function getAllByGoodType($good_type){
        return $this->select('id','good_title','old_price','new_price','thumbs_num','stock','browse_num','sell_num','good_image')->where(['good_type'=>$good_type,'goods_status'=>1])->get()->toArray();
    }

    public function getGoodImageAttribute($value)
    {
        return env('URL','').$value;
    }

    public function getRotationAttribute($value)
    {
        return json_decode($value);
    }


    /**
     * 根据条件搜索 商品
     * @param $query
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function search($query,$page,$limit){
        return $this->select('id','good_title','good_type','royalty_price','old_price','new_price','thumbs_num','stock','browse_num','sell_num','good_image')->where(['goods_status'=>1])->where('good_title','like','%'.$query.'%')->skip(($page-1)*$limit)->take($limit)->get();
    }

    /**
     * 查询 商品信息
     * @param $sku_id
     * @return mixed
     */
    public function getGoodsBySkuId($sku_id){
        return $this->where('id',$sku_id)->first();
    }

    /**
     * 更新库存 根据商品ID
     * @param $goods_id
     * @param $type
     * @param $num
     * @return mixed
     */
    public function updateStock($goods_id,$type,$num){
        if($type == 1){
            $int = $this->where(['id'=>$goods_id])->update(['stock'=>DB::raw("stock + $num")]);
        }else{
            DB::beginTransaction();
            try{
                $int = $this->where(['id'=>$goods_id])->update(['stock'=>DB::raw("stock - $num")]);
                DB::commit();
            }catch (\Exception $e){
                DB::rollBack();
                return 0;
            }
        }
        return $int;
    }
    public function getCart(){
        return $this->belongsTo(Cart::class,'id','sku_id');
    }
}