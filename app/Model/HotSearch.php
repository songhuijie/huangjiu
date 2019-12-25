<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

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

}