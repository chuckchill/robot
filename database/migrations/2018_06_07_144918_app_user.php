<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nick_name')->comment('昵称')->nullable();
            $table->string('gender')->comment('性别')->nullable();
            $table->string('birthday')->comment('生日')->nullable();
            $table->string('profile_img')->comment('头像')->nullable();
            $table->string('status')->comment('生日')->nullable();
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
        Schema::dropIfExists('app_users');
    }
}
