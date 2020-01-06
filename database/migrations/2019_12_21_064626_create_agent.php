<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\DB;

class CreateAgent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->comment('用户ID');
            $table->string('user_name')->comment('用户姓名');
            $table->string('iphone')->comment('手机号');
            $table->string('city')->comment('城市');
            $table->string('address')->comment('详细地址');
            $table->string('lng')->comment('经度');
            $table->string('lat')->comment('纬度');
            $table->string('start_time')->default(0)->comment('营业起始时间');
            $table->string('end_time')->default(0)->comment('营业结束时间');
            $table->decimal('distribution_scope')->default(1.00)->comment('配送范围 默认为km');
            $table->string('status')->default(0)->comment('状态 0表示审核中  1表示审核通过 2审核不通过');
//            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
//            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->integer('created_at')->default(0)->comment('创建时间');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->comment = '代理商';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent');
    }
}
