<?php

<<<<<<< HEAD
use Illuminate\Support\Facades\Schema;
=======
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePasswordResetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
<<<<<<< HEAD
            $table->timestamp('created_at')->nullable();
=======
            $table->timestamp('created_at');
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('password_resets');
    }
}
