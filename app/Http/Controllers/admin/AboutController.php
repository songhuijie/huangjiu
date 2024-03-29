<?php


namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class AboutController extends Controller
{

    public function index(Request $request){

        if(!empty($request->input('type'))){
            $data=$request->all();
            unset($data['type']);
            unset($data['file']);
            $config=DB::table("about")->update($data);
            if($config){
                return array("code"=>0,"msg"=>"修改成功","status"=>1);
            }else{
                return array("code"=>0,"msg"=>"请修改后提交","status"=>0);
            }
        }
        $about =DB::table("about")->first();
        return view("admin/about/index",compact('about'));
    }


}