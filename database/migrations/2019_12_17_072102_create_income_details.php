<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomeDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_details', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('user_id')->comment('用户ID');
            $table->integer('income_type')->default(1)->comment('收入类型 1获取 2转账');
            $table->decimal('amount')->comment('收入金额');
            $table->decimal('surplus_amount')->comment('当前剩余金额');
            $table->integer('income_time')->comment('交易时间');
            $table->comment = '收入明细';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('income_details');
    }
}
