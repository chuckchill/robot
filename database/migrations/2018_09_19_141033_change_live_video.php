<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeLiveVideo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('live_videos', function (Blueprint $table) {
            $table->string('country')->after('province')->comment('区')->default('');
            $table->string('sicker_name')->after('country')->comment('病人')->default('');
            $table->string('sicker_idcard')->after('sicker_name')->comment('病人身份证')->default('');
            $table->string('docker_name')->after('sicker_idcard')->comment('医生')->default('');
            $table->string('docker_no')->after('docker_name')->comment('医生工号')->default('');
            $table->string('sicker_type')->after('docker_no')->comment('病人类型')->default('');
            $table->string('sicker_id')->after('sicker_type')->comment('病人')->default('');
            $table->string('device_id')->after('sicker_id')->comment('设备')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('live_videos', function ($table) {
            $table->dropColumn('country');
            $table->dropColumn('sicker_name');
            $table->dropColumn('sicker_idcard');
            $table->dropColumn('docker_name');
            $table->dropColumn('docker_no');
            $table->dropColumn('sicker_type');
            $table->dropColumn('device_id');
            $table->dropColumn('sicker_id');
        });
    }
}
