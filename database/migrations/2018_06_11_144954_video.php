<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Video extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('buid')->comment('用户id');
            $table->string('name')->comment('名称');
            $table->string('key')->comment('存储key');
            $table->tinyInteger('status')->comment('状态')->default(1);
            $table->string('refer_id')->nullable()->comment('相关id');
            $table->string('type')->nullable()->comment('分类');
            $table->string('channel')->comment('渠道')->default('qiniu');
            $table->string('remarks')->comment('描述')->default('');
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
        Schema::dropIfExists('videos', function (Blueprint $table) {
            //
        });
    }
}
