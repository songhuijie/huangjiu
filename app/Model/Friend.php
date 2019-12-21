<?php

namespace App\Model;

use App\Libraries\Lib_const_status;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'friend';
//    public $timestamps = false;
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

    protected $select = ['id','user_name','iphone','city','address','lng','lat','start_time','end_time','distribution_scope'];


    /**
     * 添加好友关系
     */
    public function FriendRelationship($user_id,$parent_id){
        $friend = $this->where(['user_id'=>$parent_id])->first();
        if($friend){
            return $this->insert(['user_id'=>$user_id,'parent_id'=>$parent_id,'parent_parent_id'=>$friend->parent_id,'best_id'=>$friend->parent_parent_id]);
        }else{
            return $this->insert(['user_id'=>$user_id,'parent_id'=>$parent_id]);
        }
    }

    /**
     * 获取好友关系
     * @param $user_id
     * @return mixed
     */
    public function GetFriend($user_id){
        return $this->where(['user_id'=>$user_id])->first();
    }

}