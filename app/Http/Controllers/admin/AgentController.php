<?php


namespace App\Http\Controllers\admin;

use App\Events\PushEvent;
use App\Libraries\Lib_config;
use App\Model\Agent;
use App\Model\Friend;
use App\Model\FriendShip;
use App\Model\Reply;
use App\Model\User;
use App\Services\WePushService;
use Session;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentController extends Controller
{

    private $agent;
    private $friend;
    private $friend_ship;
    private $user;
    public function __construct(Agent $agent,Friend $friend,User $user,FriendShip $friend_ship)
    {
        $this->agent = $agent;
        $this->friend = $friend;
        $this->friend_ship = $friend_ship;
        $this->user = $user;
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
                    if($data['status'] == 1){

                        //审核通过 需要这个用户开启权限 给当前代理 推送审核通过信息
                        $agent = $this->agent->find($id);
                        if($agent){
                           $user =  $this->user->find($agent->user_id);
                            $message_data = [
                                'name1'=>$user->user_nickname,
                                'phrase2'=>'审核通过',
                                'thing3'=>'总代理',
                            ];
                           $open_id=$user->user_openid;
                            event(new PushEvent(Lib_config::WE_PUSH_TEMPLATE_THIRD,$message_data,$open_id));
                        }
                    }
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
            if($data['type']=='edit'){
                $agent = $this->agent->getAgent($data['id']);

                $friend = $this->friend_ship->getByBest($agent->user_id);




                $first = [];
                $second = [];
                $data =$friend->toArray();
                foreach($data as $k=>$v){
                    if(empty($v['ship'])){
                        $v['user_name'] = $this->user->where('id',$v['user_id'])->value('user_nickname');
                        $first[] = $v;
                    }else{
                        $v['user_name'] = $this->user->where('id',$v['user_id'])->value('user_nickname');
                        $second[] = $v;
                    }
                }
                return view("admin/agent/info",compact('first','second'));
            }
            if($data['type']=='add'){

                $friend = $this->friend_ship->getByUser($data['user_id']);

                if($friend){
                    if($data['status'] == 0){
                        $this->friend_ship->where('user_id',$data['user_id'])->update(['status'=>0,'is_delivery'=>0]);
                    }else{
                        if(isset($data['delivery'])){
                            $this->friend_ship->where('user_id',$data['user_id'])->update(['status'=>$data['status'],'is_delivery'=>1]);
                        }else{
                            $this->friend_ship->where('user_id',$data['user_id'])->update(['status'=>$data['status']]);
                        }
                    }

                }



                return array("code"=>1,"msg"=>"更新成功","status"=>1);exit();

            }


        }

        return view("admin/agent/info");
    }
}