<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Alarmclock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alarm_clock', function (Blueprint $table) {
            $table->increments('id');
            $table->string('data')->comment('登录类型');
            $table->string('identifier')->comment('识别标识');
            $table->string('credential')->comment('授权凭证')->nullable();
            $table->string('ifverified')->comment('是否验证')->nullable();
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
        Schema::dropIfExists('alarm_clock', function (Blueprint $table) {
            //
        });
    }
}
