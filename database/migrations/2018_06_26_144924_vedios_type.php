<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VediosType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos_type', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pid')->default(0);
            $table->string('name');
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
        Schema::dropIfExists('videos_type', function (Blueprint $table) {
            //
        });
        Schema::dropIfExists('media_type', function (Blueprint $table) {
            //
        });
    }
}
