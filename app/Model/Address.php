<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Address extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'address';
    public $timestamps = false;
    protected $dateFormat = 'U';//使用时间戳方式添加
    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    // public $timestamps = false;
    protected $fillable = [
        'id','article_titile','article_img','article_titiles','article_num','article_content','created_at','updated_at','is_status','is_on'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        ''
    ];
    protected $select = ['province','city','area','address','lng','lat','name','phone'];

    /**
     * 根据用户和地址ID 获取地址
     * @param $user_id
     * @param $address_id
     * @return mixed
     */
    public function getAddress($user_id,$address_id){
        return $this->select($this->select)->where(['user_id'=>$user_id,'id'=>$address_id])->first();
    }

    /**
     * 获取当前用户 所有地址
     * @param $user_id
     * @return mixed
     */
    public function getAll($user_id){
        return $this->where(['user_id'=>$user_id])->get();
    }

    /**
     * 添加 用户地址
     * @param $data
     * @param $user_id
     * @return mixed
     */
    public function insertAddress($data,$user_id){
        $id = $this->where(['user_id'=>$user_id,'defaults'=>1])->value('id');
        if($id){
            $this->where(['id'=>$id])->update(['defaults'=>0]);
        }
        return $this->insert($data);
    }

    /**
     * 更新用户地址
     * @param $data
     * @param $user_id
     * @param $address_id
     * @return mixed
     */
    public function updateAddress($data,$user_id,$address_id){
        return $this->where(['user_id'=>$user_id,'id'=>$address_id])->update($data);
    }
    /**
     * 删除地址
     * @param $user_id
     * @param $address_id
     * @return mixed
     */
    public function del($user_id,$address_id){
        return $this->where(['user_id'=>$user_id,'id'=>$address_id])->delete();
    }


    /**
     * 设置 默认地址
     * @param $user_id
     * @param $address_id
     * @return mixed
     */
    public function setDefault($user_id,$address_id){
        $id = $this->where(['user_id'=>$user_id,'defaults'=>1])->value('id');
        if($id){
            $this->where(['id'=>$id])->update(['defaults'=>0]);
        }
        return $this->where(['id'=>$address_id,'user_id'=>$user_id])->update(['defaults'=>1]);
    }

}