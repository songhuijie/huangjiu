<?php


namespace App\Http\Controllers\admin;

use App\Model\GoodsType;
use App\Model\User;
use Session;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }
	public function index(Request $request){

		if(!empty($request->input('type'))){
			$data=$request->all();
			if($data['type']=="select"){
				$page=1;
				$size=10;
				if(!empty($data['page'])){
					$page=$data['page'];
					$size=$data['limit'];
				}
				$pages=($page-1)*$size;
				$count=DB::table("user")->count();
				$list=DB::table("user")->offset($pages)->limit($size)->get();

                if(!empty($data["keyword"])){
                    $where=" where ";
                    if(!empty($data["keyword"])){
                        $keyword=$data["keyword"];
                        $where.="and user_nickname like '%".$keyword."%'";
                    }
                    $where=preg_replace('/and/', '', $where, 1);
                    $sql="SELECT count(*) as count FROM `user`  ".$where;
                    $count=DB::select($sql,array());
                    $count=$count[0]->count;
                    $where.=" limit ".$pages.",".$size;
                    $sql1="SELECT *  FROM `user` ".$where;
                    $list=DB::select($sql1,array());
                }

				return array('code'=>0,'msg'=>'获取到数据','limit'=>$size,'page'=>$page,'count'=>$count,'data'=>$list);
			}

			if($data['type']='edit'){
				unset($data['type']);
				$label=DB::table("user")->where("id","=",$data['id'])->first();
				if($label->status==1){
					$data['status']=0;
				}else{
					$data['status']=1;
				}

				$reust=DB::table("user")->where("id","=",$data['id'])->update($data);
				if($reust){
					return array("code"=>1,"msg"=>"修改成功");
				}else{
					return array("code"=>0,"msg"=>"修改失败,请重试");
				}

			}

		}


		return view("admin/member/index");
	}

}