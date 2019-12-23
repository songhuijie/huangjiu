<?php

namespace App\Model;

use App\Libraries\Lib_const_status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Friend extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'friend';
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

    protected $select = ['user_id'];


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

    /**
     * 获取当前用户下级
     */
    public function LowerLevel($user_id){
        return $this->select($this->select)->where(['parent_id'=>$user_id])->get();
    }

    /**
     * 获取当前用户下级
     */
    public function LowerCount($user_id){
        return $this->where(['parent_id'=>$user_id])->count();
    }

    /**
     * 获取当前用户下下级
     */
    public function LowerLowerLevel($user_id){
        return $this->select($this->select)->where(['parent_parent_id'=>$user_id])->get();
    }

    /**
     * 更新贡献
     * @param $user_id
     * @param $parent_contribute_amount
     * @param int $parent_parent_contribute_amount
     * @param int $best_contribute_amount
     * @return mixed
     */
    public function updateContribution($user_id,$parent_contribute_amount,$parent_parent_contribute_amount = 0,$best_contribute_amount = 0){

        return $this->where(['user_id'=>$user_id])->update(
            [
                'parent_contribute_amount'=>DB::raw("parent_contribute_amount + $parent_contribute_amount"),
                'parent_parent_contribute_amount'=>DB::raw("parent_parent_contribute_amount + $parent_parent_contribute_amount"),
                'best_contribute_amount'=>DB::raw("best_contribute_amount + $best_contribute_amount")

            ]
        );
    }

}