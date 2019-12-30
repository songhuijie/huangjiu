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


    public function getWithdrawTimeAttribute($value)
    {

        return date('Y-m-d H:i:s',$value);
    }

    /**
     * 获取提现记录  按时间排序返回
     * @param $user_id
     * @return mixed
     */
    public function WithdrawList($user_id){
        return $this->where('user_id',$user_id)->orderBy('withdraw_time','desc')->get();
    }

    /**
     * 根据条件搜索
     * @param $param
     * @return mixed
     */
    public function getWhere($param){
        $limit = empty($param['limit'])?10:$param['limit'];
        $page = empty($param['page'])?1:$param['page'];
        $user_id = empty($param['user_id'])?null:$param['user_id'];
        if($page>0){
            $page = ($page-1)*$limit;
        }
        $query = $this;
        if($user_id){
            $query = $query->where('user_id',$user_id);
        }
        $data['data'] = $query->orderBy('id', 'asc')->offset(($page-1)*$limit)->limit($limit)->get();
        $data['count'] = $query->orderBy('id', 'asc')->offset(($page-1)*$limit)->limit($limit)->count();
        return $data;
    }
}