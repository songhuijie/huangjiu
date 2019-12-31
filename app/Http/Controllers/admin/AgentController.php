<?php


namespace App\Http\Controllers\admin;

use App\Model\Agent;
use App\Model\Reply;
use Session;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentController extends Controller
{

    private $agent;
    public function __construct(Agent $agent)
    {
        $this->agent = $agent;
    }

    public function index(Request $request){

        if(!empty($request->input('type'))){
            $data=$request->all();
            if($data['type']=="select"){
                $page=1;
                $size=5;
                if(!empty($data['page'])){
                    $page=$data['page'];
                    $size=$data['limit'];
                }
                $pages=($page-1)*$size;
                $count=DB::table("agent")->count();
                $list=DB::table("agent")->offset($pages)->limit($size)->get();
                //搜索
                if(!empty($data["keyword"])){
                    $where=" where ";
                    if(!empty($data["keyword"])){
                        $keyword=$data["keyword"];
                        $where.="and type_name like '%".$keyword."%'";
                    }
                    $where=preg_replace('/and/', '', $where, 1);
                    $sql="SELECT count(*) as count FROM agent  ".$where;
                    $count=DB::select($sql,array());
                    $count=$count[0]->count;
                    $where.=" limit ".$pages.",".$size;
                    $sql1="SELECT *  FROM agent ".$where;
                    $list=DB::select($sql1,array());
                }
                return array('code'=>0,'msg'=>'获取到数据','limit'=>$size,'page'=>$page,'count'=>$count,'data'=>$list);
            }

            if($data['type']='edit'){
                unset($data['type']);

                $label=DB::table("agent")->where("id","=",$data['id'])->first();
                if($label->status==1){
                    $data['status']=0;
                }else{
                    $data['status']=1;
                }

                $reust=DB::table("agent")->where("id","=",$data['id'])->update($data);
                if($reust){
                    return array("code"=>1,"msg"=>"修改成功");
                }else{
                    return array("code"=>0,"msg"=>"修改失败,请重试");
                }

            }

        }


        return view("admin/agent/index");
    }
    //删除
    public function status(Request $request){
        $id=$request->input('id');
        if($request->input('type')=='del'){
            $result=DB::table("agent")->where('id',$id)->delete();
        }
        if($result){
            return array('code'=>1,'msg'=>'删除成功');
        }else{
            return array('code'=>0,'msg'=>'删除失败');
        }
    }

    public function detail(Request $request){
        if(!empty($request->input('type'))){
            $data=$request->all();
            if($data['type']=='edit'){
                if(!empty($data['update'])){
                    //修改
                    $update_data=[
                        'status' =>$data['status'],
                    ];
                    $id=$data['id'];
                    $reust=DB::table("agent")->where("id","=",$id)->update($update_data);
                    if($reust){
                        return array("code"=>1,"msg"=>"修改成功","status"=>1);exit();
                    }else{
                        return array("code"=>0,"msg"=>"修改失败","status"=>1);exit();
                    }

                }

                $label=DB::table("agent")->where("id","=",$data['id'])->first();
                return view("admin/agent/detail",compact("label"));
            }
            if($data['type']=='add'){

                unset($data['type']);
                unset($data['file']);
                $data['rotation'] = json_encode($data['rotation']);
                $reust=DB::table("agent")->insert($data);
                if($reust){
                    return array("code"=>1,"msg"=>"添加成功","status"=>1);exit();
                }else{
                    return array("code"=>0,"msg"=>"添加失败","status"=>1);exit();
                }
            }


        }

        return view("admin/agent/detail");
    }

    /**
     * 设置代理
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function set(Request $request){
        if(!empty($request->input('type'))){
            $data=$request->all();
            if($data['type']=="select"){
                $size = 1;
                $page = 10;
                $count = 10;
                $list = [];
                return array('code'=>0,'msg'=>'获取到数据','limit'=>$size,'page'=>$page,'count'=>$count,'data'=>$list);
            }
        }
        return view("admin/agent/set");
    }

    /**
     * 设置代理信息
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function info(Request $request){
        if(!empty($request->input('type'))){
            $data=$request->all();
            if($data['type']=="select"){

                if(!empty($data['update'])){
                    //修改
                    $update_data=[
                        'status' =>$data['status'],
                    ];
                    $id=$data['id'];
                    $reust=DB::table("agent")->where("id","=",$id)->update($update_data);
                    if($reust){
                        return array("code"=>1,"msg"=>"修改成功","status"=>1);exit();
                    }else{
                        return array("code"=>0,"msg"=>"修改失败","status"=>1);exit();
                    }

                }else{
                    $label=DB::table("agent")->where("id","=",$data['id'])->first();
                    return view("admin/agent/info",compact("label"));
                }


            }
        }
        return view("admin/agent/set");
    }
}