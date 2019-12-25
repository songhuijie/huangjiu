<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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


    public function getGoodsDetailAttribute($value)
    {
        return json_decode($value);
    }

    public function getAddressDetailAttribute($value)
    {
        return json_decode($value);
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
     * 根据订单号更改订单状态
     * @param $order_number
     * @param $status
     * @return mixed
     */
    public function updateStatusByOrderNumber($order_number,$status){
        return $this->where(['order_number'=>$order_number])->update(['order_status'=>$status]);
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

    /**
     * 根据条件获取 --数据
     * @param $param
     * @return mixed
     */
    public function getWhere($param){

        empty($param['limit'])?$limit = 10:$limit= $param['limit'];
        empty($param['page'])?$page = 1:$page= $param['page'];
        empty($param['keyword'])?$keyword = null:$keyword= $param['keyword'];
        $order_status = isset($param['order_status'])?$param['order_status']:null;
        if($page>0){
            $page = ($page-1)*$limit;
        }
        $query = $this;
        if($keyword){
            $query = $query->where('order_name','like',"%{$keyword}%");
        }
        if($order_status != null){
            $query = $query->where('order_status',$order_status);
        }
        $data['data'] = $query->orderBy('id', 'asc')->offset(($page-1)*$limit)->limit($limit)->get();
        $data['count'] = $query->orderBy('id', 'asc')->count();
        return $data;
    }
}