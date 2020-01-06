<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('article_title')->comment('文章标题');
            $table->string('article_titles')->comment('文章副标题');
            $table->string('article_img')->comment('文章图片');
            $table->integer('article_num')->default(0)->comment('浏览量');
            $table->text('article_content')->comment('内容');
            $table->integer('created_at')->comment('发布时间');
            $table->integer('updated_at')->comment('更新时间');
            $table->integer('is_status')->default(1)->comment('1为启用2为不启用');
            $table->integer('is_on')->default(1)->comment('1为首页推荐2为非首页推荐');
            $table->comment = '文章表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article');
    }
}
