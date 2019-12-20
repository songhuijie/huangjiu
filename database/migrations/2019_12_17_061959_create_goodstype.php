<?php

use \Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\DB;

class CreateGoodstype extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goodstype', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type_name')->comment('分类名称');
            $table->integer('sort')->comment('权重');
            $table->integer('pathid')->default(0)->comment('所属上一级id');
            $table->index('pathid');
            $table->comment = '商品分类';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goodstype');
    }
}
