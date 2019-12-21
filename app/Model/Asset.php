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
    public function updateRoyaltyBalance($data){
        $royalty_balance = $data['royalty_balance'];
        return $this->where(['user_id'=>$data['user_id']])->update(['royalty_balance'=>DB::raw("royalty_balance + $royalty_balance")]);
    }

}