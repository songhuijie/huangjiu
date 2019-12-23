<?php

namespace App\Model;

use App\Libraries\Lib_const_status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Asset extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'asset';
//    public $timestamps = false;
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

    protected $select = ['id','user_name','iphone','city','address','lng','lat','start_time','end_time','distribution_scope'];


    /**
     * 更新财产
     */
    public function updateRoyaltyBalance($data,$status=0){
        $royalty_balance = $data['royalty_balance'];
        if($status == 1){
            return $this->where(['user_id'=>$data['user_id']])->update(['agent_balance'=>DB::raw("agent_balance + $royalty_balance")]);
        }else{
            return $this->where(['user_id'=>$data['user_id']])->update(['royalty_balance'=>DB::raw("royalty_balance + $royalty_balance")]);
        }
    }

    /**
     * 获取余额
     * @param $user_id
     * @return int|string
     */
    public function getBalance($user_id){
        $result = $this->where('user_id',$user_id)->first();
        if($result){
            return bcadd($result->royalty_balance,$result->agent_balance,2);
        }else{
            return 0;
        }
    }

}