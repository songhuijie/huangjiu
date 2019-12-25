<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentSet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_set', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('agent_user_id')->comment('总代理用户');
            $table->integer('user_id')->comment('设置成代理的用户');
            $table->integer('is_agent')->comment('设置为代理用户 0表示不是 1表示是');
            $table->integer('is_delivery')->comment('设置为发货 0表示不是 1表示是');
            $table->integer('agent_id')->comment('代理ID');
            $table->comment = '代理设置表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_set');
    }
}
