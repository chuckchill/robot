<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/7/5
 * Time: 17:10
 */

use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Admin\Permission::truncate();
        $permission = config("admin.permission_seeder");
        $str = "INSERT INTO `admin_permissions` (`id`, `name`, `label`, `description`, `cid`, `icon`, `created_at`, `updated_at`,`is_menu`)VALUES";
        $date = date("Y-m-d");
        foreach ($permission as $item) {
            $start = $item['start'];
            $str .= "({$start}, '{$item['label']}', '{$item['name']}', '', 0, '{$item['icon']}', '{$date}', '{$date}',0),";
            foreach ($item['children'] as $child) {
                $is_menu = strpos($child['label'], "index") == false ? 0 : 1;
                $start++;
                $str .= "({$start}, '{$child['label']}', '{$child['name']}', '', {$item['start']}, '', '{$date}', '{$date}',{$is_menu}),";
            }
        }
        \DB::insert(substr($str, 0, -1));
    }
}