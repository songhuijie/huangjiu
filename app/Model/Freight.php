<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Freight extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'freight';
    public $timestamps = false;
    protected $dateFormat = 'U';//使用时间戳方式添加
    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    // public $timestamps = false;
    protected $fillable = [
        'id',''
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        ''
    ];
    protected $select = ['id','user_id','province','city','area','address','lng','lat','name','phone'];

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
     * 获取所有有运费的
     * @return mixed
     */
    public function getAll(){
        return $this->orderBy('sort','desc')->get();
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

    /**
     * 根据条件搜索
     * @param $param
     * @return mixed
     */
    public function getWhere($param){
        $limit = empty($param['limit'])?10:$param['limit'];
        $page = empty($param['page'])?1:$param['page'];
        $keyword = empty($param['keyword'])?null:$param['keyword'];
        $user_id = empty($param['user_id'])?null:$param['user_id'];
        if($page>0){
            $page = ($page-1)*$limit;
        }
        $query = $this;


        $data['data'] = $query->orderBy('id', 'asc')->orderBy('sort','desc')->offset(($page-1)*$limit)->limit($limit)->get();
        $data['count'] = $query->orderBy('id', 'asc')->count();
        return $data;
    }

}