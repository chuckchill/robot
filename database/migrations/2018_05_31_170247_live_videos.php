<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LiveVideos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_videos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('channel')->comment('渠道')->default('qiniu');
            $table->string('key')->comment('存储key');
            $table->string('uid')->comment('用户id');
            $table->string('name')->comment('name');
            $table->tinyInteger('status')->comment('状态')->default(1);
            $table->string('remarks')->comment('描述');
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
        Schema::dropIfExists('live_videos', function (Blueprint $table) {
            //
        });
    }
}