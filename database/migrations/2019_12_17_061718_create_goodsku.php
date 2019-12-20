<?php

use \Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\DB;

class CreateGoodsku extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goodsku', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('skuname')->comment('');
            $table->double('price')->comment('当前规格价格');
            $table->integer('stock')->comment('当前规格库存');
            $table->integer('goods_id')->comment('商品ID');
            $table->index('goods_id');
            $table->comment = '商品规格表';
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goodsku');
    }
}
