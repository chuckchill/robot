<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Bootpage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boot_page', function (Blueprint $table) {
            $table->increments('id');
            $table->string('imgsrc',200)->comment('图片地址');
            $table->integer('status')->comment('状态')->default(1);
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
        Schema::table('boot_page', function (Blueprint $table) {
            //
        });
    }
}
