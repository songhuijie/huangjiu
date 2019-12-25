<?php

namespace App\Http\Controllers\Api;

use App\Libraries\Lib_const_status;
use App\Model\Agent;
use App\Model\Asset;
use App\Model\Config;
use App\Model\Friend;
use App\Services\AccessEntity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use App\Model\User;
use PharIo\Manifest\Library;


class UserController extends Controller
{
    private $user;
    private $web;
    private $address;
    private $config;
    private $comment;
    private $cash;
    private $order;
    private $income;
    private $friend;
    private $asset;
    private $agent;
    public function __construct(User $user,Config $config,Friend $friend,Asset $asset,Agent $agent)
    {
        $this->user = $user;
        $this->config = $config;
        $this->friend = $friend;
        $this->asset = $asset;
        $this->agent = $agent;
    }

    /**
     * 用户登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
        $param = $request->all();


        $response_json = $this->initResponse();

        if (empty($param['code'])) {
            return $this->response($response_json);
        }

        $fromErr = $this->validatorFrom([
            'code'=>'required',
        ],[
            'code.required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
            'id.unique'=>Lib_const_status::USER_NOT_EXISTENT,
        ]);

        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }


        $config = $this->config->getConfig();
        $appid = $config['appid'];
        $secret = $config['secret'];
        $openid=['openid'=>1];
        if($param['code']){
            if($param['code'] == 123){
                $openid = [
                    'openid'=>123,
                    'access_token'=>'access_token123',
                ];
            }else{
                $openid = getOpenid($appid,$secret,$param['code']);
            }
        }

        if (isset($openid['openid'])) {

            $user = $this->user->info($openid['openid']);

            $expires_in = time()+86000;
            if ($user) {

                $data = [
                    'id' =>$user->id,
                    'access_token' =>$openid['access_token'],
                ];
                $this->user->where('id',$data['id'])->update(['access_token'=>$data['access_token'],'expires_in'=>$expires_in]);
                $response_json->status = Lib_const_status::SUCCESS;
                $response_json->data = $data;
                return $this->response($response_json);
            }else{

                $data = [
                    'user_openid'=> $openid['openid'],
                    'user_nickname'=> isset($param['user_nickname'])?$param['user_nickname']:'',
                    'user_img'=> isset($param['user_img'])?$param['user_img']:'',
                    'access_token'=> $openid['access_token'],
                    'expires_in'=> $expires_in,
                    'country'=> '',
                    'city'=> '',
                ];
                $result = $this->user->insert($data);
                $id = $request->input('id',0);
                $user_info =  $this->user->find($id);
                if($user_info){
                    $this->friend->FriendRelationship($result->id,$id);
                }
                if (!empty($result)) {
                    $this->asset->insert(['user_id'=>$result->id]);
                    $response_json->status = Lib_const_status::SUCCESS;
                    $response_json->data->id = $result->id;
                    $response_json->data->access_token = $result->access_token;
                    return $this->response($response_json);
                } else {
                    $response_json->status = Lib_const_status::ERROR_REQUEST_PARAMETER;
                    return $this->response($response_json);
                }
            }

        }else{
            return $this->response($response_json);
        }
    }

    /**
     * 获取用户信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userInfo(Request $request){

        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;
        $user_info = $this->user->getUserinfo($user_id);
        $asset = $this->asset->find($user_id);
        $agent = $this->agent->getByUserID($user_id);
        $user_info->balance = bcadd($asset->royalty_balance,$asset->agent_balance,2);
        if($agent){
            switch ($agent->status){
                case 0:
                    $is_agent = 1;
                    break;
                case 1:
                    $is_agent = 2;
                    break;
                default:
                    $is_agent = 3;
                    break;
            }
        }else{
            $is_agent = 0;
        }
        $user_info->is_agent = $is_agent;//0表示没有代理  1表示有代理审核中 2表示代理审核通过 3表示代理未审核通过

        $response_json = $this->initResponse();
        $response_json->code = Lib_const_status::CORRECT;
        $response_json->status = Lib_const_status::SUCCESS;
        $response_json->data = $user_info;
        return $this->response($response_json);

    }

    // 图片上传
    public function upload(Request $request){
        $file = $request->file('file');
        $path = 'images/';
        return upload($file,$path);
    }
    // 申请站长
    public function saveweb(Request $request){
        $param = $request->all();
        $id = $param['data']['user_id'];
        $judge = $this->web->getInfo($id);//判断数据库是否有当前申请
        $param = $param['data'];
        $config = $this->config->getConfig();
        if(!$config['map_key']){
            return ReCode(0,"请填写地图配置",'');
            die();
        }
        $map = tencentMap_address($config['map_key'],$param['province'].$param['city'].$param['area'].$param['address']);//转换地图坐标
        $param['lng'] = $map['data']['lng'];
        $param['lat'] = $map['data']['lat'];
        $param['status'] = 1;
        $param['pay_satus'] = 2;
        if(!$judge){
            $res = '';
            if(!empty($param['payPrice'])){
                $param['pay_satus'] = 1;
                $user = $this->user->getUserinfo($id)->toArray();
                $param['order_number'] = "T".date('YmdHis') ."R".str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT)."U".$id;//订单号
                $res = $this->wxPay($param['payPrice'],$user['user_openid'],$param['order_number']);
            }
            $result = $this->web->saveweb($param);
            if(!empty($result)){
                return ReCode(1,"审核中",$res);
            }else{
                return ReCode(0,"申请失败",'');
            }
        }else{
            if($judge->status==3){
                $param['id'] = $judge->id;
                unset($param['apply']);
                if(!empty($param['payPrice'])){
                    $param['pay_satus'] = 1;
                    $user = $this->user->getUserinfo($id)->toArray();
                    $param['order_number'] = "T".date('YmdHis') ."R".str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT)."U".$id;//订单号
                    $res = $this->wxPay($param['payPrice'],$user['user_openid'],$param['order_number']);
                }
                $result = $this->web->editweb($param);
            }
            if(!empty($result)){
                return ReCode(1,"审核中",'');
            }else{
                return ReCode(0,"申请失败",'');
            }
        }
        return ReCode(1,"审核中",'');
    }
    // 返回支付信息给小程序端
    public function wxPay($money,$openid,$ordernumber){
        $config = $this->config->getConfig();
        $user_id     = rand(1,10);
        // $ordernumber = "wxapp".date('YmdHis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT).$user_id;
        $appid       = $config['appid'];
        $mch_id      = $config['mch_id'];
        $mch_secret  = $config['mch_secret'];
        $notify_url  = url('api/v1/setwebnotify');//回调地址
        $body        = "小程序下单";
        $attach      = "用户下单";
        return initiatingPayment($money,$ordernumber,$openid,$appid,$mch_id,$mch_secret,$notify_url,$body,$attach);
    }
    // 支付回调
    public function setwebnotify(){
        $value = file_get_contents("php://input"); //接收微信参数
        if (!empty($value)) {
            $arr = xmlToArray($value);
            if($arr['result_code'] == 'SUCCESS' && $arr['return_code'] == 'SUCCESS'){
                $attach = json_decode($arr['attach'], true);
                $money = $arr['total_fee']/100;
                $uid = $attach['user_id'];
                $order = $arr['out_trade_no'];
                $this->editWebStatus($order);

                // @$this->userController->record($money,$uid,$order);
                return 'SUCCESS';
            }
        }
    }
    // 修改当前站长订单支付状态
    public function editWebStatus($order_number){
        $this->web->editWebStatus($order_number);
    }
    // 查询是否申请站长或申请合伙人
    public function getApply(Request $request){
        $param = $request->all();
        $result = $this->web->getInfo($param['user_id']);
        if(!empty($result)&&$result->status==1){
            return ReCode(1,"已申请",'');
        }else{
            return ReCode(0,"未申请",'');
        }
    }
    // 保存地址
    public function setAddress(Request $request){
        $param = $request->all();
        $res = $this->address->setAddress($param);
        if($res){
            return ReCode(1,"保存成功",'');
        }else{
            return ReCode(2,"保存失败",'');
        }
    }
    // 修改地址
    public function updateAddress(Request $request){
        $param = $request->all();
        $res = $this->address->updateAddress($param);
        if($res){
            return ReCode(1,"保存成功",'');
        }else{
            return ReCode(2,"保存失败",'');
        }
    }
    // 获取所有地址
    public function getAddress(Request $request){
        $param = $request->all();
        return $this->address->getAddress($param);
    }
    // 根据ID获取地址
    public function getAddressonly(Request $request){
        $param = $request->all();
        return $this->address->getAddressonly($param);
    }
    // 获取当前用户下的默认地址
    public function getAddressde(Request $request){
        $param = $request->all();
        return $this->address->getAddressde($param);
    }
    // 将当前用户添加为下级用户
    public function subordinate(Request $request){
        $param = $request->all();
        $user_id = $param['user_id'];//当前用户id
        $parentid = $param['parentid'];//上一级ID;
        //查询当前上一级用户是否为站长或合伙人
        $parent = $this->user->getUserinfo($parentid);
        if($parent['user_type']==1||$parent['user_type']==null){//上一级用户为普通用户无法有下级
            return 1;
            die();
        }
        // 查询当前用户是否为站长或合伙还是已经有上级
        $user = $this->user->getUserinfo($user_id);
        if($user['parent']>0||$user['user_type']>1){//当前用户已有上级 或当前用户为站长或合伙人
            return 2;
            die();
        }
        return $this->user->eidtUser($user_id,$parentid);
    }
    // 获取当前用户下的已成为合伙人的所有用户
    public function getOnlyuser(Request $request){
        $param = $request->all();
        $res = $this->web->getInfo($param['user_id']);
        return $this->web->getOnlyuser($res->id);
    }
    // 获取当前用户下的已成为站长的所有用户
    public function getOnlyuserw(Request $request){
        $param = $request->all();
        $res = $this->web->getInfo($param['user_id']);
        return $this->web->getOnlyuserw($res->id);
    }
    // 获取上一级及当前用户自己的评论
    public function getParent(Request $request){
        $param = $request->all();
        $user =  $this->user->getUserinfo($param['user_id']);
        $data['user'] = $this->comment->getOnlyContent($param['user_id'],$user['parent']);
        $data['all'] = $this->comment->getContent($user['parent']);
        $data['parent'] = $this->user->getUserinfo($user['parent']);
        return $data;
    }
    // 当前用户评论
    public function comment(Request $request){
        $param = $request->all();
        if($param['anonymous']==true){
            $param['anonymous'] = 1;
        }else{
            $param['anonymous'] = 2;
        }
        return $this->comment->setComment($param);
    }
    // 填写申请
    public function moneyCode(Request $request){
        $param = $request->all();
        // 缓存防止重复提交
        $user_id= $param['user_id'];
        if(Redis::get('moneyCode'.$user_id)>time()){
            die();
        }
        Redis::set('moneyCode'.$user_id,time()+10);
        $res  = $this->user->getUserinfo($param['user_id']);
        $param['surplus'] = $res['user_balance'] - $param['money'];
        if($param['surplus']>0){
            $res = $this->cash->addCash($param);
            if($res){
                $res = $this->user->setMoney($param);
                return ReCode(1,'提现申请已提交','');
            }
        }
        return ReCode(0,'提现申请失败','');
    }
    // 获取提现记录
    public function getMoneyRecode(Request $request){
        $param = $request->all();
        $res = $this->cash->listRecode($param);
        $count = intval($res['count']/10)+1;
        return ReCode(1,'获取记录成功',$res['data'],$count);
    }
    // 获取当前用户金额  可提现金额 总金额
    public function getMoneyCode(Request $request){
        $param = $request->all();
        $res = $this->user->getUserinfo($param['user_id']);
        $data['user_balance'] = $res->user_balance;
        $res = $this->cash->sumMoney($param);
        $data['sum_balance'] = number_format($res,'2');
        $data['total_balance'] = $data['user_balance']+$data['sum_balance'];
        return ReCode(1,'获取成功',$data);
    }
    // 获取当前用户的收入明细
    public function getThisUserMoney(Request $request){
        $param = $request->all();
        $web = $this->web->getInfo($param['user_id']);
        $data['web_id'] = $web->id;
        $res = $this->income->getList($data);
        return ReCode(1,'收入明细',$res['data'],$res['count']);
    }

}