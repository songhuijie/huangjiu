<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WithdrawLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'withdraw_log';
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


    /**
     * 获取提现记录  按时间排序返回
     */
    public function WithdrawList($user_id){
        return $this->where('user_id',$user_id)->orderBy('withdraw_time','desc')->get();
    }

}