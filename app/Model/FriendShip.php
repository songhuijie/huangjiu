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

    protected $select = ['id as friend_id','parent_id','parent_parent_id','best_id','user_id','status','is_delivery'];


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




}