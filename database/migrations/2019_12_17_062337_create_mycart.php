<?php

use \Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\DB;

class CreateMycart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mycart', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->comment('用户ID');
            $table->integer('goods_id')->comment('商品ID');
            $table->string('goods_name')->comment('商品名称');
            $table->double('new_price')->comment('新价格');
            $table->double('old_price')->comment('原价格');
            $table->integer('goods_num')->comment('购买商品数量');
            $table->index('user_id');
            $table->comment = '我的购物车';
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mycart');
    }
}
