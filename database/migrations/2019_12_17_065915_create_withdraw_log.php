<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraw_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->comment('用户ID');
            $table->integer('withdraw_type')->comment('提现类型 1微信提现 2其他提现');
            $table->decimal('amount')->comment('提现金额');
            $table->decimal('surplus_amount')->comment('当前提现剩余金额');
            $table->integer('withdraw_time')->comment('提现时间');
            $table->comment = '提现记录';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdraw_log');
    }
}
