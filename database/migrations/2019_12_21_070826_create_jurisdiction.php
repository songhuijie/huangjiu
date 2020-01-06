<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJurisdiction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jurisdiction', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('menuname')->comment('菜单名');
            $table->string('icon')->comment('图片');
            $table->string('url')->comment('路径');
            $table->integer('pid')->comment('父级id');
            $table->string('rout')->comment('用于样式');
            $table->integer('time')->comment('时间');
            $table->tinyInteger('status')->default(1)->comment('状态（禁用0，启用1）');
            $table->comment = '访问路径';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jurisdiction');
    }
}
