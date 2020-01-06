<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotSearch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hot_search', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('search_word')->comment('搜索词');
            $table->integer('search_times')->comment('搜索次数');

            $table->comment = '热门搜索词';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hot_search');
    }
}
