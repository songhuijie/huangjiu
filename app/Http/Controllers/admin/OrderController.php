<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/23
 * Time: 16:06
 */
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Services\GoodsService;
use App\Services\GoodTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller{

    private  $type = [
        0=>'待支付',
        1=>'支付成功待发货',
        2=>'已发货',
        3=>'已完成',
        4=>'维权',
        5=>'退款',
        6=>'取消',
    ];
    private $order;
    public function __construct(Order $order)
    {
        $this->order = $order;
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
                $order = $this->order->getWhere($data);
                return array('code'=>0,'msg'=>'获取到数据','limit'=>$size,'page'=>$page,'count'=>$order['count'],'data'=>$order['data']);
            }

            if($data['type']='edit'){
                unset($data['type']);
                $label=DB::table("user")->where("id","=",$data['id'])->first();
                if($label->status==1){
                    $data['status']=0;
                }else{
                    $data['status']=1;
                }

                $reust = DB::table("user")->where("id","=",$data['id'])->update($data);
                if($reust){
                    return array("code"=>1,"msg"=>"修改成功");
                }else{
                    return array("code"=>0,"msg"=>"修改失败,请重试");
                }

            }

        }

        return view("admin/order/index",['type'=>$this->type]);
    }
    //删除
    public function status(Request $request){
        $id=$request->input('id');
        if($request->input('type')=='del'){
            $result=DB::table("user")->where('id',$id)->delete();
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
                    $reust =1;
                    if($reust){
                        return array("code"=>1,"msg"=>"修改成功","status"=>1);
                    }else{
                        return array("code"=>0,"msg"=>"修改失败","status"=>1);
                    }

                }
                $order_info =$this->order->find($data['id']);
                return view("admin/order/detail",['type'=>$this->type,'order'=>$order_info]);
            }elseif($data['type'] == 'view'){
                $order_info =$this->order->find($data['id']);
                return view("admin/order/view",['type'=>$this->type,'order'=>$order_info]);
            }

        }

        return view("admin/order/detail",['type'=>$this->type]);
    }
}