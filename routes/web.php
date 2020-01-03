<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test', 'TestController@test')->name('test');
Route::get('/v2/login','Api\UserController@login');




Route::get('/', function () {
    return view('admin/index/login');
});


Route::get('message/push','admin\PushController@push');

Route::get('admin/admin','admin\AdminController@admin');
Route::any('admin/detail','admin\AdminController@detail');
Route::get('index/index', 'admin\IndexController@index');
//登录后的默认页面
Route::get('index/default', 'admin\IndexController@default');

//标签
Route::any('label/index','admin\LabelController@index');
Route::any('label/status','admin\LabelController@status');
Route::any('label/detail','admin\LabelController@detail');

//代理
Route::any('agent/index','admin\AgentController@index');
Route::any('agent/status','admin\AgentController@status');
Route::any('agent/detail','admin\AgentController@detail');

/**
 * 设置代理
 */
Route::any('agent/set','admin\AgentController@set');
Route::any('agent/info','admin\AgentController@info');

//提现记录
Route::any('withdraw/index','admin\WithdrawController@index');
Route::any('withdraw/status','admin\WithdrawController@status');
Route::any('withdraw/detail','admin\WithdrawController@detail');

//商品
Route::any('goods/index','admin\GoodsController@index');
Route::any('goods/status','admin\GoodsController@status');
Route::any('goods/detail','admin\GoodsController@detail');

//订单
Route::any('order/order','admin\OrderController@index');
Route::any('order/status','admin\OrderController@status');
Route::any('order/detail','admin\OrderController@detail');

//推荐
Route::any('recommend/index','admin\RecommendController@index');
Route::any('recommend/status','admin\RecommendController@status');
Route::any('recommend/detail','admin\RecommendController@detail');

//地址
Route::any('address/index','admin\AddressController@index');

//用户
Route::any('member/index','admin\MemberController@index');
Route::any('member/status','admin\MemberController@status');
//配置
Route::any('config/index','admin\ConfigController@index');

//配置
Route::any('about/about','admin\AboutController@index');
//文件上传
Route::any('file/img','File\FileController@img');
Route::any('layer/upload','File\FileController@LayerUpload');
//api路由组
Route::group(['namespace' => 'Api', 'prefix' => 'api'], function(){
    // 控制器在 "App\Http\Controllers\Admin" 命名空间下

    Route::get('/', [
        'as' => 'index', 'uses' => 'IndexController@login'
    ]);

    Route::any('index/index','api\IndexController@index');

});





// Route::post('config/upload','admin\ConfigController\@upload');

Route::get('admin/index', 'admin\AdminController@index');
Route::get('admin/loginLayout', 'admin\AdminController@loginLayout');
Route::post('admin/login', 'admin\AdminController@login');




//cc补充
Route::group(['middleware'=>['auth_login']],function(){
    Route::get('admin/index', 'admin\AdminController@index');
    Route::get('admin/loginLayout', 'admin\AdminController@loginLayout');
});

//后台管理员
Route::any('admin/adminuser', 'admin\AdminController@adminuser');

Route::any('admin/addadminuser', 'admin\AdminController@addadminuser');

Route::any('mechanism/admin_user','admin\mechanismController@admin_user');




