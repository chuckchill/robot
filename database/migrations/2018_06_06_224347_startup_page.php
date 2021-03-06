<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StartupPage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('startup_page', function (Blueprint $table) {
            $table->increments('id');
            $table->string('imgsrc')->comment('图片地址');
            $table->integer('status')->comment('状态')->default(1);
            $table->string('remarks')->comment('描述')->nullable();
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
        Schema::dropIfExists('startup_page', function (Blueprint $table) {
            //
        });
    }
}
