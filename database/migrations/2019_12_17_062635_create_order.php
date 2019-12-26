<?php

use \Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\DB;

class CreateOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->comment('下单用户ID');
            $table->string('order_royalty_price')->comment('订单提成价格');
            $table->string('order_total_price')->comment('合计金额');
            $table->text('goods_detail')->comment('商品详情（多商品）');
            $table->integer('order_paytype')->default(1)->comment('1微信支付,2');
            $table->integer('order_delivery')->default(0)->comment('0快递配送 1自提,2配送到家,3配送到店,4送货上门');
            $table->integer('order_status')->default(0)->comment('0待支付,1支付成功待发货,2待配送,3已发货,4完成,5退款,6取消');
            $table->text('address_detail')->comment('地址详情');
            $table->integer('is_arrive')->default(0)->comment('是否送货上门 0否 1是');//新增 是否送货上门
            $table->string('arrive_time')->default(0)->comment('送货上门时间');//新增 如果是送货 需要处理时间
            $table->integer('agent_id')->default(0)->comment('发货店铺  0为平台发货  其他对应代理商ID');
            $table->string('freight')->default(0)->comment('运费0包邮');
            $table->string('user_name')->comment('用户姓名');
            $table->bigInteger('user_phone')->comment('用户手机号');
            $table->string('order_number')->comment('订单号');
            $table->string('remarks')->default(null)->comment('备注');
            $table->string('express')->default(0)->comment('快递单号');
            $table->integer('is_comment')->default(2)->comment('1为已评论2为未评论');
//            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
//            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->integer('created_at')->default(0)->comment('创建时间');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->index('order_status');
            $table->index('user_id');
            $table->comment = '订单表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order');
    }
}
