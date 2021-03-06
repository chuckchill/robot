<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdminLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('用户');
            $table->string('uid')->comment('用户id');
            $table->string('aid')->comment('对象id');
            $table->string('type')->comment('类型');
            $table->string('model')->comment('model');
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
        Schema::dropIfExists('admin_log', function (Blueprint $table) {
            //
        });
    }
}
