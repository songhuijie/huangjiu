<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->comment('用户ID');
            $table->integer('goods_id')->comment('商品ID');
            $table->integer('sku_id')->comment('规格ID');
            $table->integer('cart_num')->comment('商品数量');
            $table->string('sku_name')->comment('名称');
            $table->string('sku_price')->comment('价格');

            $table->index('user_id');
            $table->index('goods_id');
            $table->index('sku_id');
            $table->comment = '购物车';
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart');
    }
}
