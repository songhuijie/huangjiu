<?php

namespace App\Model;

use App\Libraries\Lib_const_status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FriendShip extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'friend_relationship';
    public $timestamps = false;
//    protected $dateFormat = 'U';//使用时间戳方式添加
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

    protected $select = ['user_id','ship','status','is_delivery'];


    //建立关系
    public function userInfo(){
        return $this->hasOne(User::class,'id','user_id');
    }



    /**
     * 根据用户ID 获取信息
     * @param $user_id
     * @return mixed
     */
    public function getByUser($user_id){
        return $this->where(['user_id'=>$user_id])->first();
    }

    /**
     * 生成好友关系
     * @param $data
     * @return mixed
     */
    public function FriendRelationship($data){

        return $this->insert($data);
    }


    /**
     * 根据 best Id 获取信息
     * @param $best_id
     * @return mixed
     */
    public function getByBest($best_id){
        return $this->select($this->select)->where('best_id',$best_id)->get();
    }

    /**
     * 根据关系获取
     * @param $id
     * @return mixed
     */
    public function ShipQuery($id){
//        return $this->select($this->select)->whereIn('ship',[28])->get()->toArray();
        return $this->select($this->select)->whereRaw("FIND_IN_SET($id,ship)")->get();
    }

    /**
     * 根据id 获取下级用户人数
     * @param $id
     * @return mixed
     */
    public function LowerCount($id){
        return $this->select($this->select)->whereRaw("FIND_IN_SET($id,ship)")->count();
    }


    /**
     * 更新代理
     * @param $set_user_id
     * @param $best_id
     * @param $type
     * @return mixed
     */
    public function updateAgent($set_user_id,$best_id,$type){
        if($type == 1){
            return $this->where(['user_id'=>$set_user_id,'best_id'=>$best_id])->update(['is_delivery'=>$type]);
        }else{
            return $this->where(['user_id'=>$set_user_id,'best_id'=>$best_id])->update(['status'=>$type]);
        }
    }

    /**
     * 取消代理
     * @param $set_user_id
     * @param $best_id
     * @return mixed
     */
    public function updateAllAgentByID($set_user_id,$best_id){
        return $this->where(['user_id'=>$set_user_id,'best_id'=>$best_id])->update(['status'=>0,'is_delivery'=>0]);
    }

    /**
     * 用户ID  状态
     * @param $user_id
     * @param $status
     * @return mixed
     */
    public function getByStatus($user_id,$status){
        return $this->where(['user_id'=>$user_id,'status'=>$status])->first();
    }
}