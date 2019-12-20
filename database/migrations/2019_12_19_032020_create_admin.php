<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username')->comment('用户名');
            $table->string('password')->comment('密码');
            $table->string('headimg')->comment('头像');
            $table->integer('time')->comment('插入时间');
            $table->integer('role')->comment('插入时间');
            $table->integer('status')->comment('状态');
            $table->comment = '管理员';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin');
    }
}
