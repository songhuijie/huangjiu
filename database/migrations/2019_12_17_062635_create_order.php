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
            $table->string('order_image')->comment('订单缩略图第一个商品图片');
            $table->string('order_name')->comment('商品名称');
            $table->integer('order_num')->comment('订单数量');
            $table->integer('order_paytype')->default(1)->comment('1微信支付,2');
            $table->integer('order_delivery')->comment('0快递配送 1自提,2配送到家,3配送到店,4送货上门');
            $table->string('order_price')->comment('合计金额');
            $table->integer('order_status')->comment('0待支付,1支付成功待发货,2已发货,3已完成,4维权,5退款,6取消');
            $table->integer('user_id')->comment('下单用户ID');
            $table->integer('shop_id')->comment('收货店铺ID');
            $table->string('address')->comment('用户地址');
            $table->integer('is_arrive')->default(0)->comment('是否送货上门 0否 1是');//新增 是否送货上门
            $table->integer('arrive_time')->default(0)->comment('送货上门时间');//新增 如果是送货 需要处理时间
            $table->string('freight')->comment('运费0包邮');
            $table->integer('goods_id')->comment('商品ID');
            $table->integer('sku_id')->default(0)->comment('skuid');
            $table->integer('sku_name')->comment('规格名称');
            $table->string('user_name')->comment('用户姓名');
            $table->bigInteger('user_phone')->comment('用户姓名');
            $table->string('order_number')->comment('订单号');
            $table->string('express')->default(0)->comment('快递单号');
            $table->integer('setshop_id')->default(0)->comment('发货店铺0为平台发货');
            $table->string('order_qrcode')->comment('到店核销二维码');
            $table->integer('is_comment')->default(2)->comment('1为已评论2为未评论');


            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
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
