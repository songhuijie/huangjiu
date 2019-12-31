<?php

namespace App\Model;

use App\Libraries\Lib_config;
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

    protected $appends = ['is_comment'];
    /**
     * 插入订单并返回ID、
     * @param $data
     * @return mixed
     */
    public function insertOrder($data){
        return $this->insertGetId($data);
    }

    public function getIsCommentAttribute($value)
    {
        $reply = new Reply();
        $result = $reply->getReplyByOrderID($this->attributes['id'],$this->attributes['user_id']);
        if($result){
            return 1;
        }else{
            return 0;
        }
    }

    public function getGoodsDetailAttribute($value)
    {
        return json_decode($value,true);
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
    public function getOrderByID($order_id,$user_id){
        return $this->where(['id'=>$order_id,'user_id'=>$user_id])->first();
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
     * 更新订单状态 根据代理 用户 订单ID
     * @param $order_id
     * @param $agent_id
     * @param $status
     * @return mixed
     */
    public function updateStatusByAgent($order_id,$agent_id,$status){
        $order_status = $this->where(['id'=>$order_id,'agent_id'=>$agent_id])->value('order_status');
        if($status - $order_status ==1){
            return $this->where(['id'=>$order_id,'agent_id'=>$agent_id])->update(['order_status'=>$status]);
        }
    }


    /**
     * 根据订单号更改订单状态
     * @param $order_number
     * @param $status
     * @return mixed
     */
    public function updateStatusByOrderNumber($order_number,$status){
        $agent_id = $this->where(['order_number'=>$order_number])->value('agent_id');
        if($agent_id != 0){

            return $this->where(['order_number'=>$order_number])->update(['order_status'=>Lib_config::ORDER_STATUS_TWO]);
        }else{
            return $this->where(['order_number'=>$order_number])->update(['order_status'=>$status]);
        }
    }

    public function getOrderByOrderID($order_number){
        return $this->where(['order_number'=>$order_number])->first();
    }
    /**
     * 根据状态获取订单
     * @param $user_id
     * @param $status
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function getOrder($user_id,$status,$page,$limit){
        return $this->where(['order_status'=>$status,'user_id'=>$user_id])->orderBy('created_at','desc')->offset(($page-1)*$limit)->limit($limit)->get();
    }

    /**
     * 根据条件获取 --数据
     * @param $param
     * @return mixed
     */
    public function getWhere($param){

        empty($param['limit'])?$limit = Lib_config::LIMIT:$limit= $param['limit'];
        empty($param['page'])?$page = Lib_config::PAGE:$page= $param['page'];
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
        $data['data'] = $query->orderBy('id', 'asc')->orderBy('created_at','desc')->offset($page)->limit($limit)->get();
        $data['count'] = $query->orderBy('id', 'asc')->count();
        return $data;
    }


    /**
     * 代理用户获取订单
     * @param $agent_id
     * @param $status
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function getWhereByStatus($agent_id,$status,$page,$limit){
        return $this->where(['agent_id'=>$agent_id,'order_status'=>$status,'order_delivery'=>4])->orderBy('created_at','desc')->offset(($page-1)*$limit)->limit($limit)->get();
    }
    /**
     * 删除订单
     * @param $user_id
     * @param $order_id
     * @return int
     */
    public function deleteOrder($user_id,$order_id){
        $id = $this->where(['user_id'=>$user_id,'id'=>$order_id])->whereIn('order_status',[0,4,5,6])->value('id');
        if($id){
            return $this->where(['user_id'=>$user_id,'id'=>$order_id])->delete();
        }else{
            return 0;
        }
    }
}