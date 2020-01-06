<?php

namespace App\Model;

use App\Libraries\Lib_const_status;
use Illuminate\Database\Eloquent\Model;

class AgentSet extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'agent_set';
    public $timestamps = false;
//    protected $dateFormat = 'U';//使用时间戳方式添加
    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    // public $timestamps = false;
    protected $fillable = [
            'agent_user_id','user_id','agent_id'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        ''
    ];

    protected $select = ['agent_user_id','user_id','agent_id'];

    /**
     * 获取设置代理用户
     * @param $user_id
     * @return int
     */
    public function getCount($user_id){

         return  $this->where(['agent_user_id'=>$user_id])->count();

    }

    /**
     * 添加代理用户
     * @param $data
     * @return mixed
     */
    public function insertAgent($data){
        $id = $this->where(['user_id'=>$data['user_id']])->value('id');
        if(!$id){
            return $this->insert($data);
        }
    }

    /**
     * 获取用户是否获取到代理
     * @param $set_user_id
     * @return mixed
     */
    public function getUserAgent($set_user_id){
        return  $this->where(['user_id'=>$set_user_id])->first();
    }




}