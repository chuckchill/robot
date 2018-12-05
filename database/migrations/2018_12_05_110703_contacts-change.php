<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContactsChange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_users_contacts', function (Blueprint $table) {
            $table->renameColumn('uid', 'doctor_id');
            $table->renameColumn('contract_id', 'sicker_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_users_contacts', function (Blueprint $table) {
            $table->renameColumn('doctor_id', 'uid');
            $table->renameColumn('sicker_id', 'contract_id');
        });
    }
}
