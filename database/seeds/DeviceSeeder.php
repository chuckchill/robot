<?php
use Illuminate\Database\Seeder;

/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/6/9
 * Time: 17:49
 */
class DeviceSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Api\Devices::insert([
            'sno' => str_random(10),
            'name' => '',
        ]);
    }
}