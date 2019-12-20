<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'user';
    protected $dateFormat = 'U';//使用时间戳方式添加
    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    // public $timestamps = false;
    protected $fillable = [
        'id','parent','user_nickname','user_img','user_openid','user_balance','user_type','sex','country','access_token','expires_in','city','created_at','updated_at'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        ''
    ];
    // 添加新用户
    public function insert($param){
        $userdata['user_nickname'] = empty($param['nickname']) ? '' : $param['nickname'];
        $userdata['user_img'] = empty($param['headimgurl']) ? '' : $param['headimgurl'];
        $userdata['sex'] = empty($param['sex']) ? 0 : $param['sex'];
        $userdata['country'] = empty($param['country']) ? '' : $param['country'];
        $userdata['city'] = empty($param['city']) ? '' : $param['city'];
        $userdata['user_openid'] = $param['openid'];
        $userdata['access_token'] = $param['access_token'];
        $userdata['expires_in'] = $param['expires_in'];
        $userdata['user_type'] = 1;
        $userdata['user_balance'] = 0;
        $userdata['parent'] = isset($param['parent'])? $param['parent']:0;
        $result = $this->create($userdata);
        return $result;
    }
    // 查询当前用户 通过openid查询当前用户
    public function info($openid){
        return $this->where('user_openid',$openid)->first();
    }
    // 通过id查询当前用户信息
    public function getUserinfo($id){
        return $this->find($id);
    }

    /**
     * 根据用户授权token  获取用户过期时间
     * @param $access_token
     * @return mixed
     */
    public function getByAccessToken($access_token){
        return $this->select('id','user_nickname','user_img','expires_in')->where(['access_token'=>$access_token])->first();
    }
    // 删除用户
    public function delUser($param){
        return $this->destroy($param['id']);
    }
    // 获取用户列表
    public function getAlluser($param){
        empty($param['limit'])?$limit = 10:$limit= $param['limit'];
        empty($param['page'])?$page = 0:$page= $param['page'];
        empty($param['keyword'])?$keyword = null:$keyword= $param['keyword'];
        if($page>0){
            $page = ($page-1)*$limit;
        }
        $query = $this;
        if($keyword){
            $query = $query->where(DB::raw('concat(user_nickname)'),'like',"%{$keyword}%");
        }
        $data['data'] = $query->orderBy('id', 'asc')->offset($page)->limit($limit)->get();
        $data['count'] = $query->orderBy('id', 'asc')->count();
        return $data;
    }
    // 获取所有用户用来选择站长
    public function getAllUserlsit(){
        return $this->get();
    }
    // 修改当前用户类型
    public function setUsertype($id,$type){
        if($type!=2){//站长
            return $this->where("id",$id)->update(['user_type'=>3]);
        }else{//合伙人
            return $this->where("id",$id)->update(['user_type'=>2]);
        }
    }
    // 将用户修改为普通用户
    public function setUser($id){
        return $this->where("id",$id)->update(['user_type'=>1]);
    }
    // 将上一级的id 给当前用户ID
    public function eidtUser($user_id,$id){
        return $this->where("id",$user_id)->update(['parent'=>$id]);
    }
    // 增加当前用户的积分
    public function addIntegral($param){
        return $this->where("id",$param['user_id'])->increment('user_integral',$param['price']);
    }
    // 增加当前用户的余额
    public function addMoney($param){
        return $this->where("id",$param['user_id'])->increment('user_balance',$param['price']);
    }
    // 减少当前用户的积分
    public function setIntegral($param){
        return $this->where("id",$param['user_id'])->decrement('user_integral',$param['price']);
    }
    // 减少当前用户的余额
    public function setMoney($param){
        return $this->where("id",$param['user_id'])->decrement('user_balance',$param['money']);
    }
    // 拒绝提现回滚用户余额
    public function callbackMoney($user_id,$money){
        return $this->where("id",$user_id)->increment('user_balance',$money);
    }
    // 删去当前用户的上一级
    public function setParent($user_id){
        return $this->where("id",$user_id)->update(['parent'=>0]);
    }
    // 获取用户总量
    public function getCountUser(){
        return $this->count();
    }
    // 统计用余额
    public function sumUserbalance(){
        return $this->sum("user_balance");
    }
    // 统计用户积分
    public function sumUserIntegral(){
        return $this->sum('user_integral');
    }
}