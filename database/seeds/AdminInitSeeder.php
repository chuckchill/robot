<?php

use Illuminate\Database\Seeder;

class AdminInitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Admin\AdminUser::truncate();
        \App\Models\Admin\Permission::truncate();
        $permission = config("admin.permission_seeder");
        $str = "INSERT INTO `admin_permissions` (`id`, `name`, `label`, `description`, `cid`, `icon`, `created_at`, `updated_at`)VALUES";
        $date = date("Y-m-d");
        foreach ($permission as $item) {
            $start = $item['start'];
            $str .= "({$start}, '{$item['label']}', '{$item['name']}', '', 0, '{$item['icon']}', '{$date}', '{$date}'),";
            foreach ($item['children'] as $child) {
                $start++;
                $str .= "({$start}, '{$child['label']}', '{$child['name']}', '', {$item['start']}, '', '{$date}', '{$date}'),";
            }
        }
        \DB::select(substr($str, 0, -1));
        $admin = new \App\Models\Admin\AdminUser();
        $admin->id = 1;
        $admin->name = 'root';
        $admin->email = 'root@admin.com';
        $admin->password = bcrypt('root');
        $admin->save();
    }
}
