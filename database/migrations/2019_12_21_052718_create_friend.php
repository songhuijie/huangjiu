<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\DB;

class CreateFriend extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friend', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->comment('用户ID');
            $table->integer('parent_id')->comment('上一级ID');
            $table->integer('parent_parent_id')->default(0)->comment('上上一级ID');
            $table->integer('best_id')->default(0)->comment('最上级ID');
            $table->integer('status')->default(0)->comment('最上级ID用户是否有分红 0没有 1变成2级用户 2变成3级用户');
            $table->integer('is_delivery')->default(0)->comment('设置为发货 0表示不是 1表示是');
            $table->decimal('parent_contribute_amount')->default(0)->comment('上级贡献的金额 金额为整数 乘了100');
            $table->decimal('parent_parent_contribute_amount')->default(0)->comment('上上级贡献的金额 金额为整数 乘了100');
            $table->decimal('best_contribute_amount')->default(0)->comment('最上级贡献的金额 金额为整数 乘了100');
            $table->comment = '邀请关系表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('friend');
    }
}
