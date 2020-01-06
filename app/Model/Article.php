<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'article';
    protected $dateFormat = 'U';//使用时间戳方式添加
    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    // public $timestamps = false;
    protected $fillable = [
        'id','article_titile','article_img','article_titiles','article_num','article_content','created_at','updated_at','is_status','is_on'
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
     * 指示是否自动维护时间戳
     *
     * @var bool
     */
    // public $timestamps = false;
    // 获取文章列表
    public function getArticlelist($parma){
        empty($param['limit'])?$limit = 10:$limit= $param['limit'];
        empty($param['page'])?$page = 0:$page= $param['page'];
        empty($param['keyword'])?$keyword = null:$keyword= $param['keyword'];
        if($page>0){
            $page = ($page-1)*$limit;
        }
        $query = $this;
        if($keyword){
            $query = $query->where(DB::raw('concat(article_titile)'),'like',"%{$keyword}%");
        }
        $data['data'] = $query->orderBy('id', 'desc')->offset($page)->limit($limit)->get();
        $data['count'] = $query->orderBy('id', 'desc')->count();
        return $data;
    }

    // 添加文章
    public function addArticle($param){
        return $this->create($param);
    }

    // 修改文章
    public function editArticle($param){
        return $this->where("id",$param['id'])->update($param);
    }
    // 获取当前ID 的文章
    public function getFirstArticle($id){
        return $this->find($id);
    }
    // 删除文章
    public function delArticel($param){
        return $this->destroy($param['id']);
    }
    // 小程序获取文章
    public function getArtile($param){
        return $this->where("is_on",$param['is_on'])->where("is_status",1)->get();
    }
}