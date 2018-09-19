<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Sicker extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sicker', function (Blueprint $table) {
            $table->increments('id');
            $table->string('device_id')->comment('设备id');
            $table->string('province')->comment('省');
            $table->string('city')->comment('市');
            $table->string('country')->comment('区');
            $table->string('sicker_name')->comment('病人姓名');
            $table->string('sicker_idcard')->comment('身份证');
            $table->string('doctor_name')->comment('医生');
            $table->string('doctor_no')->comment('医生工号');
            $table->string('type')->comment('病人类型');
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
        Schema::dropIfExists('sicker', function (Blueprint $table) {});
    }
}
