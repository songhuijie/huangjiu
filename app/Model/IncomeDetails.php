<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class IncomeDetails extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'income_details';
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


    /**
     * 插入收入明细
     * @param $data
     * @return mixed
     */
    public function insertIncome($data){
        return $this->insert($data);
    }


    /**
     * 根据用户ID从日志获取最后余额
     */
    public function getAmount($user_id){
        return $this->where(['user_id'=>$user_id])->orderBy('income_time','desc')->value('surplus_amount');
    }

    /**
     * 获取收益明细  按时间排序返回
     */
    public function incomeList($user_id){
        return $this->where('user_id',$user_id)->orderBy('income_time','desc')->get();
    }


}