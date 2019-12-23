<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'order';
    public $timestamps =false;
    protected $dateFormat = 'U';//使用时间戳方式添加
    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    // public $timestamps = false;
    protected $fillable = [
    ];
    protected $primaryKey = 'id';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        ''
    ];

    /**
     * 插入订单并返回ID、
     * @param $data
     * @return mixed
     */
    public function insertOrder($data){
        return $this->insertGetId($data);
    }


    /**
     * 更新订单状态
     * @param $order_id
     * @param $user_id
     * @param $status
     * @return mixed
     */
    public function updateStatus($order_id,$user_id,$status){
        return $this->where(['id'=>$order_id,'user_id'=>$user_id])->update(['order_status'=>$status]);
    }

    /**
     * 根据状态获取订单
     * @param $user_id
     * @param $status
     * @return mixed
     */
    public function getOrder($user_id,$status){
        return $this->where(['order_status'=>$status,'user_id'=>$user_id])->get();
    }
}