<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\DB;

class CreateFriendRelationship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friend_relationship', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->comment('用户ID');
            $table->text('ship')->nullable()->comment('好友关系');
            $table->integer('best_id')->comment('最上级ID');
            $table->integer('status')->default(0)->comment('状态 0 表示不是代理商  1 1级代理  2 2级代理');
            $table->integer('is_delivery')->default(0)->comment('设置为发货 0表示不是 1表示是');
            $table->comment = '好友关系';
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('friend_relationship');
    }
}
