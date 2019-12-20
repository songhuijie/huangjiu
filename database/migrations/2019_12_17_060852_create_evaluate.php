<?php

use \Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\DB;

class CreateEvaluate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluate', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_name')->comment('用户昵称');
            $table->text('evaluate')->comment('用户评价');
            $table->string('goods_image')->comment('评价上传的图片');
            $table->integer('goods_id')->comment('评价的商品ID');
            $table->integer('status')->comment('0审核中,审核通过1,未通过2');
            $table->integer('order_id')->comment('评价的订单ID');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->index('goods_id');
            $table->index('order_id');
            $table->comment = '商品评价表';
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluate');
    }
}
