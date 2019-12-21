<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('role')->comment('角色名');
            $table->string('jurisdictionid')->comment('权限id集合都好隔开');
            $table->integer('time')->comment('权限id集合都好隔开');
            $table->tinyInteger('status')->default(1)->comment('状态（0禁用，1启用）');
            $table->comment = '权限';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role');
    }
}
