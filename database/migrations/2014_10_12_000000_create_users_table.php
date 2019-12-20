<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('users', function (Blueprint $table) {
//            $table->bigIncrements('id');
//            $table->string('name');
//            $table->string('email')->unique();
//            $table->timestamp('email_verified_at')->nullable();
//            $table->string('password');
//            $table->rememberToken();
//            $table->timestamps();
//        });
        Schema::create('user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('parent')->comment('上一级ID 此ID为上一级推荐 下一级不为下一级 推荐上一级');
            $table->string('user_nickname')->comment('用户昵称');
            $table->string('user_img')->comment('用户头像');
            $table->float('user_balance')->comment('用户可提现余额');
            $table->integer('sex')->comment('1为男2为女');
            $table->string('country')->default(null)->comment('用户国家');
            $table->string('city')->default(null)->comment('用户省市');
            $table->string('access_token')->comment('access_token');
            $table->integer('expires_in')->comment('token 过期时间');
            $table->string('user_openid')->comment('用户openid');
            $table->integer('user_type')->comment('1普通会员 2合伙人 3站长');
            $table->integer('created_at')->comment('添加时间');
            $table->integer('updated_at')->comment('修改时间');
            $table->integer('user_integral')->default(0)->comment('积分');
            $table->comment = '用户表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
