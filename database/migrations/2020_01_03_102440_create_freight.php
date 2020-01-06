<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\DB;

class CreateFreight extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freight', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('regions')->comment('多地区');
            $table->decimal('price')->default(0)->comment('初始重量_运费价格');
            $table->decimal('over_price')->default(0)->comment('超出初始重量价格');
            $table->integer('sort')->default(0)->comment('排序 - 如果同地区选择多个 按照排序的来选择 0-99  越大');
            $table->comment = '运费';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('freight');
    }
}
