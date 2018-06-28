<?php
use Illuminate\Database\Seeder;

/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/6/9
 * Time: 17:49
 */
class AppSeeder extends Seeder
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
            'name' => str_random(10),
        ]);
        $pwd = Hash::make("123456");
        $userInfo = new \App\Services\ModelService\UserInfo();
        $user = new \App\Models\Api\User();
        $user->nick_name = "测试1";
        $user->gender = "男";
        $user->birthday = "2018-02-05";
        $user->save();
        $userInfo->registerData("mobile", "13025447440", "", $user->id);
        $userInfo->registerData("sys", "test1", $pwd, $user->id);

        $user = new \App\Models\Api\User();
        $user->nick_name = "测试2";
        $user->gender = "女";
        $user->birthday = "2018-02-05";
        $user->save();
        $userInfo->registerData("mobile", "13025447441", "", $user->id);
        $userInfo->registerData("sys", "test2", "", $user->id);

    }
}