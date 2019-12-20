<?php


namespace App\Http\Controllers\admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class IndexController extends Controller
{

	public function index(Request $request){
		//权限
		$data=DB::table('jurisdiction')->where('status',1)->get()->map(function ($value) {return (array)$value;})->toArray();
		$user=DB::table("admin")->where("username","=",Session::get("username"))->select("username","headimg","role")->first();

		//角色
        $role=DB::table("role")->where("id",$user->role)->select('jurisdictionid')->first();

		$jurisdiction=explode(',',$role->jurisdictionid);

	    foreach ($data as $key => $value) {
	      foreach ($jurisdiction as $k => $v) {
	         if($data[$key]['id']==$v){
	            $value['ceshi']=1;
	            $list[$key]=$value;
	        }else{
	          $list[$key]=$value;
	        }
	      }
	    }
        $admin_menu=$this->list_to_tree($list, $pk='id', $pid = 'pid', $child = 'items', $root = 0);

        return view("admin/index/index")->with(['user'=>$user,'admin_menu'=>$admin_menu]);

	}

	 //菜单权限
    public function list_to_tree($list, $pk='id', $pid = 'pid', $child = 'items', $root = 0) {
        //创建Tree
        $tree = array();

        if (is_array($list)) {
            //创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] = &$list[$key];
            }
            foreach ($list as $key => $data) {
                //判断是否存在parent_id
                $parantId = $data[$pid];
                if ($root == $parantId) {
                    $tree[] = &$list[$key];
                } else {
                    if (isset($refer[$parantId])) {
                        $parent = &$refer[$parantId];
                        $parent[$child][] = &$list[$key];
                    }
                }
            }
        }
        return $tree;
    }

    public function default(){


    	return view("admin/index/default");
    }
}