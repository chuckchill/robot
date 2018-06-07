<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table='admin_permissions';
    protected $dateFormat = "Y-m-d H:i:s";
    public function roles()
    {
        return $this->belongsToMany(Role::class,'admin_permission_role','permission_id','role_id');
    }

}
