<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->comment('用户ID');
            $table->string('province')->comment('省份');
            $table->string('city')->comment('城市');
            $table->string('area')->comment('区县');
            $table->string('address')->comment('详细地址');
            $table->string('lng')->comment('经度');
            $table->string('lat')->comment('纬度');
            $table->integer('defaults')->default(0)->comment('0不默认1默认');
            $table->string('name')->comment('收件人名称');
            $table->bigInteger('phone')->comment('手机号');
            $table->integer('label')->default(1)->comment('1家 2公司 3学校');
            $table->comment = '地址表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('address');
    }
}
