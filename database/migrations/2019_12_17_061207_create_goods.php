<?php

use \Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\DB;

class CreateGoods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('good_title')->comment('标题');
            $table->string('good_type')->comment('商品分类');
            $table->float('royalty_price')->comment('提成价格');
            $table->string('old_price')->comment('原价格');
            $table->string('new_price')->comment('新价格');
            $table->integer('thumbs_num')->default(0)->comment('点赞次数');
            $table->integer('stock')->comment('库存');
            $table->integer('browse_num')->default(0)->comment('浏览量');
            $table->integer('sell_num')->default(0)->comment('销量');
            $table->string('good_image')->comment('商品大图');
            $table->text('rotation')->comment('轮播图json格式');
            $table->text('detail')->comment('详情');
            $table->float('freight')->comment('运费0包邮');
            $table->integer('goods_status')->comment('1为出售中 2为下架');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->index('good_type');
            $table->comment = '商品';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods');
    }
}
