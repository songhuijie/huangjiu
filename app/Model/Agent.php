<?php

namespace App\Model;

use App\Libraries\Lib_const_status;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'agent';
//    public $timestamps = false;
//    protected $dateFormat = 'U';//使用时间戳方式添加
    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    // public $timestamps = false;
    protected $fillable = [
            'user_id','user_name','iphone','city','address','lng','lat','start_time','end_time','distribution_scope'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        ''
    ];

    protected $select = ['id','user_name','iphone','city','address','lng','lat','start_time','end_time','distribution_scope','user_img'];

    /**
     * 添加用户代理
     * @param $all
     * @param $user_id
     * @return int
     */
    public function insertAgent($all,$user_id){

         $id = $this->where(['user_id'=>$user_id])->value('id');
         if(!$id){
             return $this->insert($all);
         }else{
             $all['status'] = 0;
             return $this->where(['user_id'=>$user_id])->update($all);
         }

    }

    /**
     * 更新代理信息
     * @param $all
     * @param $user_id
     * @param $agent_id
     * @return mixed
     */
    public function updateAgent($all,$user_id,$agent_id){
       return $this->where(['user_id'=>$user_id,'id'=>$agent_id])->update($all);
    }

    /**
     * 根据城市获取当前是否成功有代理
     * @param $city
     * @return mixed
     */
    public function getAgentByCity($city){

        return $this->where(['city'=>$city,'status'=>1])->first();
    }
    /**
     * 获取代理
     * @param $city
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getNeedAgent($city){


        if($city){
            return $this->select($this->select)->where(['city'=>$city])->get();
        }else{
            return $this->select($this->select)->all();
        }
    }

    /**
     * 根据代理ID 和user_id 获取代理信息
     * @param $agent_id
     * @param $user_id
     * @return mixed
     */
    public function getAgentById($agent_id,$user_id){
        return $this->where(['user_id'=>$user_id,'id'=>$agent_id])->first();
    }


    /**
     * 根据代理ID 和user_id 获取代理信息
     * @param $agent_id
     * @return mixed
     */
    public function getAgent($agent_id){
        return $this->where(['id'=>$agent_id])->first();
    }
    /**
     * 根据用户ID 返回代理信息
     * @param $user_id
     * @param $status
     * @return mixed
     */
    public function getByUserID($user_id,$status=null){
        if($status == null){
            return $this->where(['user_id'=>$user_id])->first();
        }
        return $this->where(['user_id'=>$user_id,'status'=>1])->first();
    }


    public function userImg(){
        return $this->belongsTo(User::class,'user_id','id');
    }

}