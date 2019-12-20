<?php


namespace App\Http\Controllers\admin;

use App\Model\GoodsType;
use Session;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GoodsController extends Controller
{
    private $good_type;
    public function __construct(GoodsType $good_type)
    {
        $this->good_type = $good_type;
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
				$count=DB::table("goods")->count();
				$list=DB::table("goods")->offset($pages)->limit($size)->get();
				//搜索
				if(!empty($data["keyword"])){
					$where=" where ";
					if(!empty($data["keyword"])){
						$keyword=$data["keyword"];
						$where.="and type_name like '%".$keyword."%'";
					}
					 $where=preg_replace('/and/', '', $where, 1);
					 $sql="SELECT count(*) as count FROM goods  ".$where;
					 $count=DB::select($sql,array());
					 $count=$count[0]->count;
					 $where.=" limit ".$pages.",".$size;
					 $sql1="SELECT *  FROM goodstype ".$where;
					 $list=DB::select($sql1,array());
				}

				$goods_type = $this->good_type->getAll()->toArray();
                $goods_type_array = array_column($goods_type,'type_name','id');
				foreach($list as $k=>$v){
                    $list[$k]->goods_name = isset($goods_type_array[$v->good_type])?$goods_type_array[$v->good_type]:'';
                }
				return array('code'=>0,'msg'=>'获取到数据','limit'=>$size,'page'=>$page,'count'=>$count,'data'=>$list);
			}

			if($data['type']='edit'){
				unset($data['type']);
				$label=DB::table("goods")->where("id","=",$data['id'])->first();
				if($label->status==1){
					$data['status']=0;
				}else{
					$data['status']=1;
				}

				$reust=DB::table("goods")->where("id","=",$data['id'])->update($data);
				if($reust){
					return array("code"=>1,"msg"=>"修改成功");
				}else{
					return array("code"=>0,"msg"=>"修改失败,请重试");
				}

			}

		}


		return view("admin/goods/index");
	}
	//删除
	public function status(Request $request){
		$id=$request->input('id');
		if($request->input('type')=='del'){
			$result=DB::table("goods")->where('id',$id)->delete();
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
                    $data['rotation'] = json_encode($data['img']);
                    $id = $data['id'];
                    unset($data['file']);
                    unset($data['type']);
                    unset($data['img']);
                    unset($data['update']);
                    unset($data['id']);
					$reust=DB::table("goods")->where("id","=",$id)->update($data);
					if($reust){
						return array("code"=>1,"msg"=>"修改成功","status"=>1);exit();
					}else{
						return array("code"=>0,"msg"=>"修改失败","status"=>1);exit();
					}

				}
				$label=DB::table("goods")->where("id","=",$data['id'])->first();
                $goods_type = $this->good_type->getAll()->toArray();
                $goods_type_array = array_column($goods_type,'type_name','id');
                $label->rotation = json_decode($label->rotation);
				return view("admin/goods/detail",['label'=>$label,'goods_type'=>$goods_type_array]);exit();
			}
			if($data['type']=='add'){


			    $data['rotation'] = json_encode($data['img']);
				unset($data['file']);
				unset($data['type']);
				unset($data['img']);
				$reust=DB::table("goods")->insert($data);
				if($reust){
					return array("code"=>1,"msg"=>"添加成功","status"=>1);exit();
				}else{
					return array("code"=>0,"msg"=>"添加失败","status"=>1);exit();
				}
			}


		}

        $goods_type = $this->good_type->getAll()->toArray();
        $goods_type_array = array_column($goods_type,'type_name','id');

		return view("admin/goods/detail",['goods_type'=>$goods_type_array]);
	}
}