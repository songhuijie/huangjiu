<?php


namespace App\Http\Controllers\admin;

use App\Libraries\Lib_config;
use App\Model\Config;
use App\Model\User;
use App\Model\WithdrawLog;
use Illuminate\Support\Facades\Log;
use Session;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawController extends Controller
{
    private $withdraw_log;
    private $config;
    private $user;
    public function __construct(WithdrawLog $withdraw_log,Config $config,User $user)
    {
        $this->withdraw_log = $withdraw_log;
        $this->config = $config;
        $this->user = $user;
    }

    public function index(Request $request){

        if(!empty($request->input('type'))){
            $data=$request->all();

            if($data['type']=="select"){

                $withdraw = $this->withdraw_log->getWhere($data);
                return array('code'=>0,'msg'=>'获取到数据','limit'=>$withdraw['limit'],'page'=>$withdraw['page'],'count'=>$withdraw['count'],'data'=>$withdraw['data']);
            }

            if($data['type']='edit'){
                unset($data['type']);
//                $label=DB::table("withdraw_log")->where("id","=",$data['id'])->first();
//
//
//
//                $reust=DB::table("withdraw_log")->where("id","=",$data['id'])->update($data);
//                if($reust){
//                    return array("code"=>1,"msg"=>"修改成功");
//                }else{
//                    return array("code"=>0,"msg"=>"修改失败,请重试");
//                }
            }

        }


        return view("admin/withdraw_log/index");
    }
    //删除
    public function status(Request $request){
        $id=$request->input('id');
        if($request->input('type')=='del'){
            $result=DB::table("withdraw_log")->where('id',$id)->delete();
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

                        //
                        $withdraw = $this->withdraw_log->find($data['id']);
                        if($withdraw){
                            $user = $this->user->find($withdraw->user_id);
                            $openid = $user->user_openid;
                            $amount = $withdraw->amount;
                            $config = $this->config->getConfig();
                            $appid = $config->appid;
                            $mchid = $config->mch_id;
                            $mch_secret = $config->mch_secret;
                            $key_pem = $config->key_pem;
                            $cert_pem = $config->cert_pem;
                            $desc = '提现';
                            $partner_trade_no = 'Z'.date('YmdHis').rand();
                            //企业给用户转账
                            $result = transferAccounts($appid,$mchid,$openid,$desc,$partner_trade_no,$amount,$mch_secret,$key_pem,$cert_pem);

                            if($result['result_code']=='success'){
                                $reust=DB::table("withdraw_log")->where("id","=",$id)->update($update_data);
                                return array("code"=>1,"msg"=>"已成功提现","status"=>1);
                            }else{
                                return array("code"=>0,"msg"=>$result['err_code_des'],"status"=>1);
                            }
                        }

                    }

                    $reust=DB::table("withdraw_log")->where("id","=",$id)->update($update_data);
                    if($reust){
                        return array("code"=>1,"msg"=>"修改成功","status"=>1);exit();
                    }else{
                        return array("code"=>0,"msg"=>"修改失败","status"=>1);exit();
                    }

                }

                $label=DB::table("withdraw_log")->where("id","=",$data['id'])->first();
                return view("admin/withdraw_log/detail",compact("label"));
            }
            if($data['type']=='add'){

                unset($data['type']);
                unset($data['file']);
                $data['rotation'] = json_encode($data['rotation']);
                $reust=DB::table("withdraw_log")->insert($data);
                if($reust){
                    return array("code"=>1,"msg"=>"添加成功","status"=>1);exit();
                }else{
                    return array("code"=>0,"msg"=>"添加失败","status"=>1);exit();
                }
            }


        }

        return view("admin/withdraw_log/detail");
    }
}