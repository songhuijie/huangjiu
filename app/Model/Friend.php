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

    protected $select = ['id as friend_id','parent_parent_id','best_id','parent_id','user_id','status','is_delivery'];


    /**
     * 更新代理状态或者发货状态
     * @param $set_user_id
     * @param $type
     * @param $status
     * @return mixed
     */
    public function updateAgent($set_user_id,$type,$status){
        if($type == 1){
            return $this->where('parent_id',$set_user_id)->update(['is_delivery'=>$status]);
        }else{
            return $this->where('parent_id',$set_user_id)->update(['status'=>$status]);
        }
    }

    /**
     * 更新代理状态或者发货状态
     * @param $id
     * @param $type
     * @param $status
     * @return mixed
     */
    public function updateAgentByID($id,$type,$status){
        if($type == 1){
            return $this->where('parent_id',$id)->update(['is_delivery'=>$status]);
        }else{
            return $this->where('parent_id',$id)->update(['status'=>$status]);
        }
    }

    /**
     * 取消代理和送货
     * @param $id
     * @param $status
     * @return mixed
     */
    public function updateAllAgentByID($id,$status){

        return $this->where('parent_id',$id)->update(['is_delivery'=>$status,'status'=>$status]);

    }
    /**
     * 添加好友关系
     * @param $user_id
     * @param $parent_id
     * @return mixed
     */
    public function FriendRelationship($user_id,$parent_id){
        $own = $this->where(['user_id'=>$user_id])->first();
        if(!$own){
            $friend = $this->where(['parent_id'=>$parent_id])->first();
            if($friend){
                return $this->insert(['user_id'=>$user_id,'parent_id'=>$parent_id,'parent_parent_id'=>$friend->parent_parent_id,'best_id'=>$friend->best_id,'status'=>$friend->status,'is_delivery'=>$friend->is_delivery]);
            }else{
                return $this->insert(['user_id'=>$user_id,'parent_id'=>$parent_id]);
            }
        }

    }

    /**
     * 获取好友关系
     * @param $user_id
     * @return mixed
     */
    public function GetFriend($user_id){
        return $this->where(['parent_id'=>$user_id])->first();
    }


    /**
     * 获取好友关系
     * @param $user_id
     * @return mixed
     */
    public function GetFriendInit($user_id){
        return $this->where(['user_id'=>$user_id])->first();
    }

    /**
     * 获取好友关系
     * @param $friend_id
     * @return mixed
     */
    public function GetFriendByBestOrParent($friend_id){
        return $this->where(['parent_id'=>$friend_id])->first();
    }


    /**
     * 获取当前用户下级
     * @param $user_id
     * @return mixed
     */
    public function LowerLevel($user_id){
        return $this->select($this->select)->where(['parent_parent_id'=>$user_id])->orWhere(['best_id'=>$user_id])->get();
    }

    /**
     * 获取当前用户状态
     * @param $user_id
     * @return mixed
     */
    public function CurrentLevel($user_id){
        return $this->select($this->select)->where(['parent_id'=>$user_id])->first();
    }

    /**
     * 获取当前用户下级
     * @param $user_id
     * @return mixed
     */
    public function LowerCount($user_id){
        return $this->where(['parent_id'=>$user_id])->count();
    }


    /**
     * 计算贡献
     * @param $user_id
     * @return string
     */
    public function Contribution($user_id){
        $amount_one = $this->where(['parent_id'=>$user_id])->sum('parent_contribute_amount');
        $amount_two = $this->where(['parent_parent_id'=>$user_id])->sum('parent_parent_contribute_amount');
        $amount_three = $this->where(['best_id'=>$user_id])->sum('best_contribute_amount');

        return bcadd($amount_three,bcadd($amount_one,$amount_two,2),2);
    }

    /**
     * 获取当前用户下下级
     * @param $user_id
     * @param $status
     * @return mixed
     */
    public function LowerLowerLevel($user_id,$status){
        if($status == 2){
            return $this->select($this->select)->where(['parent_parent_id'=>$user_id])->get();
        }else{
            return $this->select($this->select)->where(['best_id'=>$user_id])->get();
        }
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