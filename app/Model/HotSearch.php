<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class HotSearch extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'hot_search';
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
    protected $select = ['search_word'];
    protected $select_all = ['id','search_word','search_times'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        ''
    ];

    public function getHotWord(){

        return $this->select($this->select)->where('search_times','>','10')->get();
    }


    /**
     * 根据词获取数据
     * @return mixed
     */
    public function getByWords($data){

        return $this->select($this->select_all)->whereIn('search_word',$data)->get();
    }




}