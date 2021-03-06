<?php

namespace App\Http\Controllers\Api;

use App\Libraries\Lib_config;
use App\Libraries\Lib_const_status;
use App\Model\Address;
use App\Model\Collection;
use App\Model\Config;
use App\Model\Goods;
use App\Model\GoodsType;
use App\Services\AccessEntity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use App\Model\User;
use App\Model\HotSearch;
use PharIo\Manifest\Library;


class GoodsController extends Controller
{
    private $user;
    private $good;
    private $good_type;
    private $hos_search;
    private $collect;
    public function __construct(User $user,Goods $good,GoodsType $good_type,HotSearch $hos_search,Collection $collect)
    {
        $this->user = $user;
        $this->good = $good;
        $this->good_type = $good_type;
        $this->hos_search = $hos_search;
        $this->collect = $collect;
    }

    /**
     * 获取商品列表 首页
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function GoodsList(Request $request)
    {
        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'page'=>'int',
        ],[
            'int'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }
        $goods_type = $request->input('goods_type');

        $good_type = $this->good_type->getFirst();
        if(!$goods_type){
            $goods_type = $good_type->id;
        }
        $page = isset($all['page'])?$all['page']:Lib_config::PAGE;
        $limit = Lib_config::LIMIT;
        $response_json = $this->initResponse();

        $goods_types = $this->good_type->getAll();

        $goods_list =$this->good->getAllByGoodType($goods_type,$page,$limit);

        $response_json->status = Lib_const_status::SUCCESS;
        $response_json->data->goods_type = $goods_types;
        $response_json->data->goods_list = $goods_list;

        return $this->response($response_json);

    }

    /**
     * 获取商品详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function GoodsDetail(Request $request){
        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'goods_id'=>'required',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }
        $response_json = $this->initResponse();

        $detail = $this->good->find($all['goods_id']);


        $user_id = 0;
        $access_token = $request->header('accessToken');
        $user = new User();
        $token_array = $user->getByAccessToken($access_token);
        if($token_array && $token_array->expires_in > time()){
            $user_id = $token_array->id;
        }
        $collect = $this->collect->getCollect($user_id);
        $collects = array_column($collect,'goods_id');
        $detail->is_collect = in_array($detail->id,$collects)?1:0;
        $detail->reply;
        if($detail){
            $response_json->status = Lib_const_status::SUCCESS;
            $response_json->data = $detail;
        }else{
            $response_json->status = Lib_const_status::GOODS_NOT_EXISTENT;
        }
        return $this->response($response_json);
    }

    /**
     * 搜索商品
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function SearchGoods(Request $request){

        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'query'=>'required',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }

        $response_json = $this->initResponse();

        $page = isset($all['page'])?$all['page']:1;

        $query = $all['query'];
        Artisan::call("handle:word $query");
        $detail = $this->good->search($query,$page,Lib_config::SEARCH_LIMIT);
        if($detail){
            $response_json->status = Lib_const_status::SUCCESS;
            $response_json->data = $detail;
        }
        return $this->response($response_json);
    }

    /**
     * 返回热门搜索词
     */
    public function HotSearch(){

        $response_json = $this->initResponse();

        $words = $this->hos_search->getHotWord();
        $response_json->status = Lib_const_status::SUCCESS;
        $words = $words->toArray();
        $response_json->data = array_column($words,'search_word');
        return $this->response($response_json);

    }


}