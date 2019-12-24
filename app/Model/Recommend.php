<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Recommend extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'recommend';
    public $timestamps = false;
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
    protected $select = ['id','title','author','cover','view','created_at','updated_at'];


    /**
     * 获取 推荐列表
     * @return mixed
     */
    public function getAll(){
        return $this->select($this->select)->get();
    }


    public function getCoverAttribute($value)
    {
        return env('URL').$value;
    }

//    public function goods(){
//        return $this->hasMany(Goods::class, 'good_type', 'id');
//    }

}