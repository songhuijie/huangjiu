<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/20
 * Time: 16:53
 */
namespace  App\Http\Api;

use App\Http\Controllers\Controller;
use App\Libraries\Lib_const_status;
use App\Model\Collection;
use App\Services\AccessEntity;

class CollectionController extends Controller{

    private $collection;

    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }


    /**
     * 用户收藏列表
     */
    public function CollectionList(){

        $response_json = $this->initResponse();
        $access_entity = AccessEntity::getInstance();
        $user_id = $access_entity->user_id;

        $cart_list = $this->collection->collectionList($user_id);

        $response_json->status = Lib_const_status::SUCCESS;
        $response_json->data = $cart_list;
        return $this->response($response_json);

    }

    /**
     * 收藏添加
     */
    public function CollectionAdd(){

    }


    /**
     * 收藏删除
     */
    public function CollectionDel(){

    }
}