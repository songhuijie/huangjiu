<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\Article;
class ArticleController extends Controller
{
    private $user;
    private $article;
    public function __construct(User $user,Article $article)
    {
        $this->user = $user;
        $this->article = $article;
    }
    // 加载文章列表
    public function artList(Request $request){
        if($request->ajax()){
            $param = $request->all();
            $res = $this->article->getArticlelist($param);
            return ReCode(0,"查询成功",$res['data'],$res['count']);
        }else{
            return view("Article/list");
        }
    }
    // 添加文章
    public function addArticle(Request $request){
        if($request->ajax()){
            $param = $request->except('file');
            $res = $this->article->addArticle($param);
            return ReCode(1,"添加成功","");
        }else{
            return view("Article/addArticle");
        }
    }
    // 修改文章
    public function editArticle(Request $request){
        $param = $request->except('file');
        if($request->ajax()){
            $res = $this->article->editArticle($param);
            return ReCode(1,"保存成功","");
        }else{
            $res = $this->article->getFirstArticle($param['id']);
            return view("Article/editArticle",['res'=>$res]);
        }
    }
    // 删除文章
    public function delArticle(Request $request){
        $param = $request->all();
        $this->article->delArticel($param);
        return ReCode(200,"删除成功",'');
    }
}