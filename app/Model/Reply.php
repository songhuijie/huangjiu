<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'reply';
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

    /**
     * 获取评论信息 根据goods_id
     */
    public function getReply($goods_id){
        return $this->where('goods_id',$goods_id)->get();
    }

    /**
     * 插入评论消息
     * @param $data
     * @return mixed
     */
    public function insertReply($data){
        return $this->insert($data);
    }
}