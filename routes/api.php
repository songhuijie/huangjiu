<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    $api->group(['prefix' => 'v1', 'Middleware' => 'CheckMiddle', 'namespace' => 'App\Http\Controllers\Api'], function ($api) {
        $api->post('/login','UserController@login');
        $api->any('/notify','OrderController@notify');

        //商品首页
        $api->post('/goods/list','GoodsController@GoodsList');
        //商品详情
        $api->post('/goods/detail','GoodsController@GoodsDetail');
        //根据精度获取代理
        $api->post('/agent/accuracy','AgentController@AgentAccuracy');
        //根据精度获取详细地址
        $api->post('/accuracy/address','AgentController@AccuracyAddress');

        //推荐列表
        $api->post('/recommend/list','RecommendController@RecommendList');
        //推荐文章详情
        $api->post('/recommend/detail','RecommendController@RecommendDetail');
        //关于信息
        $api->post('/about/about','RecommendController@AboutInfo');


        // 获取首页文章
        $api->group(['middleware'=>'CheckAccessToken'],function($api){
            //用户信息
            $api->post('/userInfo','UserController@userInfo');
            $api->post('/set/phone','UserController@setPhone');
            //获取用户地址
            $api->post('/addressList','AddressController@addressList');
            //添加用户地址
            $api->post('/addressAdd','AddressController@addressAdd');
            //更新用户地址
            $api->post('/addressUpdate','AddressController@addressUpdate');
            //设置 用户地址 默认地址
            $api->post('/addressDefault','AddressController@AddressDefault');
            //删除用户地址
            $api->post('/addressDel','AddressController@addressDel');



            //商品详情
//            $api->post('/goods/detail','GoodsController@GoodsDetail');
            //商品搜索
            $api->post('/goods/search','GoodsController@SearchGoods');
            //搜索热词
            $api->post('/goods/hoswords','GoodsController@HotSearch');
            //商品首页
//            $api->post('/goods/list','GoodsController@GoodsList');


            //购物车列表
            $api->post('/cart/list','CartController@CartList');
            //购物添加
            $api->post('/cart/add','CartController@CartAdd');
            //购物车修改
            $api->post('/cart/edit','CartController@CartEdit');
            //购物车删除
            $api->post('/cart/del','CartController@CartDel');

            //收藏列表
            $api->post('/collection/list','CollectionController@CollectionList');
            //收藏添加
            $api->post('/collection/add','CollectionController@CollectionAdd');
            //收藏删除
            $api->post('/collection/del','CollectionController@CollectionDel');

            //代理申请
            $api->post('/agent/apply','AgentController@Apply');
            //设置代理信息
            $api->post('/agent/set','AgentController@Setting');
            //设置代理用户
            $api->post('/agent/setagent','AgentController@setAgent');
            //取消代理用户
            $api->post('/agent/cancelagent','AgentController@cancelAgent');
            //获取代理信息
            $api->post('/agent/get','AgentController@getAgent');
            //更改代理订单状态
            $api->post('/agent/changeOrder','AgentController@changeOrder');

            //获取下级
            $api->post('/user/lower','AgentController@SubordinateUser');

            //获取代理的订单
            $api->post('/agent/orderlist','AgentController@getAgentList');

            //订单-下单
            $api->post('/order/order','OrderController@order');

            //订单支付
            $api->post('/order/pay','OrderController@pay');
            //确认收货
            $api->post('/order/confirm','OrderController@ConfirmReceipt');
            //订单列表
            $api->post('/order/list','OrderController@OrderList');
            //取消订单
            $api->post('/order/cancel','OrderController@OrderCancel');
            //订单物流信息
            $api->post('/order/expressInfo','OrderController@ExpressInformation');


            //收入明细
            $api->post('/income/list','IncomeController@IncomeList');
            //提现记录
            $api->post('/withdraw/list','IncomeController@WithdrawList');
            //申请提现
            $api->post('/withdraw/withdraw','IncomeController@withdraw');


            //申请提现
            $api->post('/relay/relay','ReplyController@Relay');

//            $api->post('/cart/list','CartController@CartList');

        });

    });


});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
