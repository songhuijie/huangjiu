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

    protected $select = ['id as friend_id','parent_id','parent_parent_id','best_id','user_id','status','is_delivery'];


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
     * 后台  ---  更新代理状态或者发货状态
     * @param $user_id
     * @param $status
     * @param $delivery
     * @return mixed
     */
    public function updateAgentAdmin($user_id,$status,$delivery = null){
        if($status == 0){
            return $this->where('parent_id',$user_id)->update(['status'=>0,'is_delivery'=>0]);
        }else{

            if($delivery == null){
                return $this->where('parent_id',$user_id)->update(['status'=>$status]);
            }else{
                return $this->where('parent_id',$user_id)->update(['status'=>$status,'is_delivery'=>1]);
            }
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
                if($friend->user_id == 0){
                    return $this->where(['id'=>$friend->id])->update(['user_id'=>$user_id]);
                }else{
                    return $this->insert(['user_id'=>$user_id,'parent_id'=>$parent_id,'parent_parent_id'=>$friend->parent_parent_id,'best_id'=>$friend->best_id,'status'=>$friend->status,'is_delivery'=>$friend->is_delivery]);
                }
            }else{
                $friend = $this->where(['user_id'=>$parent_id])->first();
                if($friend){
                    if($friend->best_id != 0){
                        $friend->parent_parent_id  = $friend->best_id;
                    }
                    return $this->insert(['user_id'=>$user_id,'parent_id'=>$parent_id,'parent_parent_id'=>$friend->parent_id,'best_id'=>$friend->parent_parent_id,'status'=>0,'is_delivery'=>0]);
                }
                return $this->insert(['user_id'=>$user_id,'parent_id'=>$parent_id]);
            }
        }

    }

    /**
     * 普通好友
     * 创建好友关系
     * @param $user_id
     * @param $parent_id
     * @param $parent_parent_id
     * @param $best_id
     * @param $status
     * @return mixed
     */
    public function InsertFriend($user_id,$parent_id,$parent_parent_id,$best_id,$status){
        if($best_id != 0){
            $parent_parent_id = $best_id;
        }
        if($status == 1){
            return $this->insert(['user_id'=>0,'parent_id'=>$user_id,'parent_parent_id'=>$parent_id,'best_id'=>$parent_parent_id,'status'=>0,'is_delivery'=>1]);
        }else{
            return $this->insert(['user_id'=>0,'parent_id'=>$user_id,'parent_parent_id'=>$parent_id,'best_id'=>$parent_parent_id,'status'=>$status,'is_delivery'=>0]);
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
        return $this->select($this->select)->where(['parent_id'=>$user_id])->orWhere(['parent_parent_id'=>$user_id])->orWhere(['best_id'=>$user_id])->get()->toArray();
    }

    /**
     * 获取一个用户下级
     * @param $user_id
     * @return mixed
     */
    public function LowerLevelOne($user_id){
        return $this->select($this->select)->where(['parent_id'=>$user_id,'is_delivery'=>1])->orWhere(['parent_parent_id'=>$user_id,'is_delivery'=>1])->orWhere(['best_id'=>$user_id,'is_delivery'=>1])->first();
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
     * @param $own_user_id
     * @return string
     */
    public function Contribution($user_id,$own_user_id){
        $amount_one = $this->where(['user_id'=>$user_id,'parent_id'=>$own_user_id])->sum('parent_contribute_amount');
        $amount_two = $this->where(['user_id'=>$user_id,'parent_parent_id'=>$own_user_id])->sum('parent_parent_contribute_amount');
        $amount_three = $this->where(['user_id'=>$user_id,'best_id'=>$own_user_id])->sum('best_contribute_amount');

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