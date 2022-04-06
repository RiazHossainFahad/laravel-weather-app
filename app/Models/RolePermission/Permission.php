<?php

namespace App\Models\RolePermission;

use Spatie\Permission\Models\Permission as ParentPermissionModel;

class Permission extends ParentPermissionModel
{
    public function children()
    {
        return $this->hasMany(Permission::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Permission::class, 'parent_id');
    }
}