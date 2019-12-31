<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'collection';
    protected $dateFormat = 'U';//使用时间戳方式添加
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


    protected $select = ['goods_id'];

    public function goods()
    {
        return $this->hasOne(Goods::class,'id','goods_id');
    }

    /**
     * 获取收藏列表
     * @param $user_id
     * @return mixed
     */
    public function collectionAll($user_id){
        //with 数据不在同一级，弱关系
        return $this->select('id','goods_id')->where(['user_id'=>$user_id])->with(['goods'=>function($query){
                            $query->select('id','good_title','good_type','royalty_price','old_price','new_price','thumbs_num','stock','browse_num','sell_num','good_image');
                }])->get();
    }


    /**
     * 添加 收藏商品
     * @param $data
     * @return bool
     */
    public function InsertCollect($data){
        $id = $this->where(['user_id'=>$data['user_id'],'goods_id'=>$data['goods_id']])->value('id');
        if(!$id){
            return $this->insert($data);
        }
        return false;
    }

    /**
     * 删除收藏
     * @param $data
     * @return bool
     */
    public function DelCollect($data){
        $id = $this->where(['user_id'=>$data['user_id'],'goods_id'=>$data['goods_id']])->value('id');
        if($id){
            return $this->where(['user_id'=>$data['user_id'],'goods_id'=>$data['goods_id']])->delete();
        }
        return false;
    }

    /**
     *  获取用户收藏
     * @param $user_id
     * @return mixed
     */
    public function getCollect($user_id){
        return $this->select('goods_id')->where(['user_id'=>$user_id])->get()->toArray();
    }


}