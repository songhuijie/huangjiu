<?php


namespace App\Http\Controllers\admin;

use App\Libraries\Lib_config;
use App\Model\Address;
use App\Model\Freight;
use App\Services\CityServices;
use Session;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FreightController extends Controller
{
    private $freight;
    public function __construct(Freight $freight)
    {
        $this->freight = $freight;
    }

    public function index(Request $request){

        if(!empty($request->input('type'))){
            $data=$request->all();
            if($data['type']=="select"){
                $page = Lib_config::PAGE;
                $size = Lib_config::LIMIT;
                if(!empty($data['page'])){
                    $page=$data['page'];
                    $size=$data['limit'];
                }
                $address = $this->freight->getWhere($data);
                return array('code'=>0,'msg'=>'获取到数据','limit'=>$size,'page'=>$page,'count'=>$address['count'],'data'=>$address['data']);
            }

            if($data['type']='edit'){
                unset($data['type']);
                if(1){
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


        return view("admin/freight/index");
    }


    //删除
    public function status(Request $request){
        $id=$request->input('id');
        if($request->input('type')=='del'){
            $result=$this->freight->where('id',$id)->delete();
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

                    $reust=DB::table("goods")->where("id","=",$id)->update($data);
                    if($reust){
                        return array("code"=>1,"msg"=>"修改成功","status"=>1);exit();
                    }else{
                        return array("code"=>0,"msg"=>"修改失败","status"=>1);exit();
                    }

                }
                $freight = $this->freight->where("id","=",$data['id'])->first();

                return view("admin/freight/detail",['label'=>$freight]);
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

        $city = CityServices::AllCity();
        CityServices::HIERARCHY();

        return view("admin/freight/detail");
    }
}