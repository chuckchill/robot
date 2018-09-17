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
            $table->string('provice')->comment('省');
            $table->string('city')->comment('市');
            $table->string('country')->comment('区');
            $table->string('name')->comment('区');
            $table->string('doctor')->comment('医生');
            $table->string('doctor_no')->comment('医生工号');
            $table->string('type')->comment('病人类型');
            $table->string('idcard_no')->comment('身份证');
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
