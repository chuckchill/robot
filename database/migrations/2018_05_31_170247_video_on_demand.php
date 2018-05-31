<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VideoOnDemand extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('video_on_demand', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bucket')->comment('用户');
            $table->string('channel')->comment('渠道')->default('qiniu');
            $table->string('key')->comment('存储key');
            $table->string('uid')->comment('用户id');
            $table->string('filename')->comment('model');
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
        Schema::dropIfExists('video_on_demand', function (Blueprint $table) {
            //
        });
    }
}
