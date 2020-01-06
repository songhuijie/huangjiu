<?php

use \Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\DB;

class CreateConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('appid')->comment('appid');
            $table->string('mch_id')->comment('mch_id');
            $table->string('mch_secret')->comment('mch_secret');
            $table->string('secret')->comment('secret');
            $table->string('map_key')->comment('map_key');
            $table->string('map_secret_key')->comment('map_secret_key');
            $table->string('access_token')->comment('access_token');
            $table->string('time_add')->default(0)->comment('time_add');
            $table->string('cert_pem')->comment('cert_pem');
            $table->string('key_pem')->comment('key_pem');
            $table->text('aboutUs')->comment('关于我们');
            $table->string('express_key')->comment('快递鸟的Key');
            $table->string('EBusinessID')->comment('快递鸟的商户ID');
            $table->string('sms_key')->comment('阿里云api_key');
            $table->string('sms_secret')->comment('阿里云api_secret');
            $table->comment = '配置表';
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('config');
    }
}
