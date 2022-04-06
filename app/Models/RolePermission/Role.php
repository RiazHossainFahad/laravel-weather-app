<?php

namespace App\Models\RolePermission;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Role as ParentRoleModel;


class Role extends ParentRoleModel
{
    protected $appends = ['is_name_editable', 'is_deletable'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }

    protected function getDefaultRoles()
    {
        return [
            config('custom.default_role_for_admin', 'Admin'),
            config('custom.default_role_for_user', 'User'),
        ];
    }

    public function getIsNameEditableAttribute()
    {
        return in_array($this->attributes['name'], $this->getDefaultRoles()) ? false : true;
    }

    public function getIsDeletableAttribute()
    {
        return in_array($this->attributes['name'], $this->getDefaultRoles()) ? false : true;
    }

    public static function availableRoles()
    {
        return [
            'Admin', 'User'
        ];
    }

    /**
     * Get the creater that owns the Role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Get the updater that owns the Role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}