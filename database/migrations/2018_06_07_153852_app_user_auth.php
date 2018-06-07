<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppUserAuth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_users_auth', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->comment('用户外键')->nullable();
            $table->string('identity_type')->comment('登录类型');
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
        Schema::dropIfExists('app_users_auth');
    }
}
