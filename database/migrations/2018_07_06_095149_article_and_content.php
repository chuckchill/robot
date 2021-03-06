<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class ArticleAndContent
 */
class ArticleAndContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //文章
        Schema::create('article', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('标题');
            $table->string('type')->comment('分类');
            $table->tinyInteger('status')->comment('状态')->default(1);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article', function (Blueprint $table) {});
    }
}
