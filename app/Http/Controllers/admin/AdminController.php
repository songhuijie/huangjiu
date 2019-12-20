<?php


namespace App\Http\Controllers\admin;

use Session;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * @param array $data 要转换的结果集
     * @param int $pk id  标记id
     * @param int $pid pid  标记父级字段
     * @param string $child items 标记子集字段
     * @param int $root  pid 标记父级字段为0
     */
 

    //登录
    public function login(Request $request){
       
        if($request->all()){
            $data=$request->all();
            $data['password']=md5($data['password']);
            $admin=DB::table("admin")->where($data)->first();
            if(!$admin){
                return array("code"=>0,"msg"=>"账号或密码输入错误",'status'=>0);
            }
            if($admin->status==0){
                return array("code"=>0,"msg"=>"账号被禁用,请联系超级管理员",'status'=>1);
            }
            session::put("username",$admin->username);
            return array("code"=>1,"msg"=>"登录成功",'status'=>2);
        }
        return view('./login');

    }
    //管理员
    public function admin(Request $request){
        if($request->input("type")){
            $data=$request->all();
            if($data["type"]=="select"){
                $page=1;
                $size=5;
                if(!empty($data['page'])){
                    $page=$data['page'];
                    $size=$data['limit'];
                }
                $pages=($page-1)*$size;
                $count=DB::table("admin")->count();
                $list=DB::table("admin")->offset($pages)->limit($size)->get();
                //搜索
                if(!empty($data["keyword"])||!empty($data['typeid'])){
                    $where=" where ";
                    if(!empty($data["keyword"])){
                        $keyword=$data["keyword"];
                        $where.="and username like '%".$keyword."%' ";
                    }
                    // if(!empty($data['typeid'])){
                    //     $type=$data['typeid'];
                    //     $where.=" and  xsk_guide.typeid=".$type;
                    // }
                     $where=preg_replace('/and/', '', $where, 1);
                     $sql="SELECT count(*) as count FROM admin ".$where;
                     $count=DB::select($sql,array());
                     $count=$count[0]->count;
                     $where.=" limit ".$pages.",".$size;
                     $sql1="SELECT *  FROM  admin  ".$where;
                     $list=DB::select($sql1,array());
                }
                return array('code'=>0,'msg'=>'获取到数据','limit'=>$size,'page'=>$page,'count'=>$count,'data'=>$list);
            }
            if($data['type']='edit'){
                unset($data['type']);
                $guide=DB::table("admin")->where("id","=",$data['id'])->first();
                if($guide->status==1){
                    $data['status']=0;
                }else{
                    $data['status']=1;
                }

                $reust=DB::table("admin")->where("id","=",$data['id'])->update($data);
                if($reust){
                    return array("code"=>1,"msg"=>"修改成功");
                }else{
                    return array("code"=>0,"msg"=>"修改失败,请重试");
                }

            }
            if($request->input('type')=='del'){
                $id=$data['id'];
                $result=DB::table("guide")->where('id',$id)->delete();
                if($result){
                    return array('code'=>1,'msg'=>'删除成功');
                }else{
                    return array('code'=>0,'msg'=>'删除失败');
                }
            }
        }
        return view('admin/index/admin');
    }

    public function detail(Request $request){

        if(!empty($request->input('type'))){
            $data=$request->all();
            $data['time']=time();
            if($data['type']=="edit"){
                if(!empty($data['update'])){
                    unset($data['file']);
                    unset($data['type']);
                    unset($data['update']);
                    $data['password']=md5($data['password']);
                    //权限未写

                    $reust=DB::table("admin")->where("id",$data['id'])->update($data);
                    if($reust){
                        return array("code"=>1,"msg"=>"修改成功","status"=>1);
                    }else{
                        return array("code"=>0,"msg"=>"修改失败","status"=>1);
                    }

                }
                $admin=DB::table("admin")->where('id',$data['id'])->first();
                return view('admin/index/detail',compact('admin')); 
            }
            if($data['type']=="add"){
                unset($data['file']);
                unset($data['type']);
                //权限未写
                $data['role']="1";
                $data['password']=md5($data['password']);

                $reust=DB::table("admin")->insert($data);
                if($reust){
                    return array("code"=>1,"msg"=>"添加成功","status"=>1);
                }else{
                    return array("code"=>0,"msg"=>"添加失败","status"=>1);
                }
            }
        }


       return view('admin/index/detail'); 
    }


}