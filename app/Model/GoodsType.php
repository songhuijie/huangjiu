<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodsType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'goodstype';
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


    public function getAll(){
        return $this->all();
    }

    public function getFirst(){
        return $this->first();
    }

//    public function goods(){
//        return $this->hasMany(Goods::class, 'good_type', 'id');
//    }

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }

    public function getRotationAttribute($value)
    {
        $array = json_decode($value);
        if($array){
            foreach($array as $k=>&$v){
                $v = env('URL','https://huangjiu.xcooteam.cn/').$v;
            }
        }
        return $array;
    }
}