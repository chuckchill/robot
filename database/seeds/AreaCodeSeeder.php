<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/7/5
 * Time: 10:10
 */

use Illuminate\Database\Seeder;

class AreaCodeSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Common\AreaCode::truncate();
        $sql = file_get_contents(__DIR__ . "/area_code.sql");
        \Illuminate\Support\Facades\DB::insert($sql);
        $date = date("Y-m-d h:i:s");
        \Illuminate\Support\Facades\DB::update('update area_code set created_at =?,updated_at=?', [$date, $date]);
    }
}