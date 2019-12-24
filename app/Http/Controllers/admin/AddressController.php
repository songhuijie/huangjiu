<?php


namespace App\Http\Controllers\admin;

use App\Libraries\Lib_config;
use App\Model\Address;
use Session;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    private $address;
    public function __construct(Address $address)
    {
        $this->address = $address;
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
				$address = $this->address->getWhere($data);
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


		return view("admin/address/index");
	}

}