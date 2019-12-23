<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/20
 * Time: 16:53
 */
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Libraries\Lib_const_status;
use App\Model\Collection;
use App\Model\Goods;
use App\Services\AccessEntity;
use Illuminate\Http\Request;

class CollectionController extends Controller{

    private $collection;
    private $goods;

    public function __construct(Collection $collection,Goods $goods)
    {
        $this->collection = $collection;
        $this->goods = $goods;
    }


    /**
     * 用户收藏列表
     */
    public function CollectionList(){

        $response_json = $this->initResponse();
        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;

        $cart_list = $this->collection->collectionAll($user_id);

        $response_json->status = Lib_const_status::SUCCESS;
        $response_json->data = $cart_list;
        return $this->response($response_json);

    }

    /**
     * 收藏添加
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function CollectionAdd(Request $request){
        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'goods_id'=>'required|int',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
            'int'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);

        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }
        $good  = $this->goods->find($all['goods_id']);
        $response_json = $this->initResponse();
        if($good){
            $access_entity = AccessEntity::getInstance();
            $all['user_id'] = $access_entity->user_id;
            $int = $this->collection->InsertCollect($all);
            if($int){
                $response_json->status = Lib_const_status::SUCCESS;
            }else{
                $response_json->status = Lib_const_status::GOODS_HAS_BEEN_COLLECTED;
            }

        }else{
            $response_json->status = Lib_const_status::GOODS_NOT_EXISTENT;
        }

        return $this->response($response_json);
    }


    /**
     * 收藏删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function CollectionDel(Request $request){

        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'goods_id'=>'required|int',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
            'int'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);

        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }

        $response_json = $this->initResponse();
        $access_entity = AccessEntity::getInstance();
        $all['user_id'] = $access_entity->user_id;
        $int = $this->collection->DelCollect($all);
        if($int){
            $response_json->status = Lib_const_status::SUCCESS;
        }else{
            $response_json->status = Lib_const_status::GOODS_COLLECTION_CANCELLED;
        }
        return $this->response($response_json);
    }
}